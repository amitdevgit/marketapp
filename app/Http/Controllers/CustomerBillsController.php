<?php

namespace App\Http\Controllers;

use App\Models\CustomerBill;
use App\Models\CustomerBillItem;
use App\Models\MerchantBillItem;
use App\Models\Customer;
use App\Models\Product;
use App\Services\BillEditLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerBillsController extends Controller
{
    /**
     * Display a listing of customer bills.
     */
    public function index(): View
    {
        $customerBills = CustomerBill::with(['customer', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('customer-bills.index', compact('customerBills'));
    }

    /**
     * Show the form for creating a new customer bill.
     */
    public function create(): View
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        return view('customer-bills.create', compact('customers'));
    }

    /**
     * Store a newly created customer bill.
     */
        public function store(Request $request): RedirectResponse
        {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'bill_date' => 'required|date',
                'notes' => 'nullable|string',
            ]);

            DB::transaction(function () use ($validated) {
                $customerId = $validated['customer_id'];
                $billDate = $validated['bill_date'];

                // Find or create a single bill for this customer on the selected date
                $customerBill = CustomerBill::firstOrCreate(
                    [
                        'customer_id' => $customerId,
                        'bill_date' => $billDate,
                    ],
                    [
                        'total_amount' => 0,
                        'notes' => $validated['notes'],
                    ]
                );

                // Load ALL merchant bill items for this customer (regardless of date)
                $merchantBillItems = MerchantBillItem::where('customer_id', $customerId)
                    ->with(['product', 'merchantBill.merchant'])
                    ->get();

                if ($merchantBillItems->isEmpty()) {
                    throw new \Exception('No products found for this customer in merchant bills.');
                }

                // Prevent adding the same merchant items twice
                $existingLinkedItemIds = $customerBill->items()
                    ->pluck('merchant_bill_item_id')
                    ->filter()
                    ->toArray();

                foreach ($merchantBillItems as $merchantItem) {
                    if (in_array($merchantItem->id, $existingLinkedItemIds, true)) {
                        continue;
                    }

                    $customerBill->items()->create([
                        'merchant_bill_item_id' => $merchantItem->id,
                        'product_id' => $merchantItem->product_id,
                        'quantity' => $merchantItem->quantity,
                        'rate' => $merchantItem->rate,
                        'misc_adjustment' => $merchantItem->misc_adjustment,
                        'net_quantity' => $merchantItem->net_quantity,
                        'total_amount' => $merchantItem->total_amount,
                    ]);
                }

                // Recalculate total
                $recalculatedTotal = $customerBill->items()->sum('total_amount');
                $customerBill->update(['total_amount' => $recalculatedTotal]);

                // Log the creation/update
                BillEditLogService::logCustomerBillCreated($customerBill->id, [
                    'customer_id' => $customerBill->customer_id,
                    'bill_date' => $customerBill->bill_date->format('Y-m-d'),
                    'total_amount' => $customerBill->total_amount,
                    'notes' => $customerBill->notes,
                    'items_count' => $customerBill->items()->count(),
                ]);
            });

            return redirect()->route('customer-bills.index')
                ->with('success', 'Customer Bill generated/updated successfully!');
        }

    /**
     * Display the specified customer bill.
     */
    public function show(CustomerBill $customerBill): View
    {
        $customerBill->load(['customer', 'items.product', 'items.merchantBillItem.merchantBill.merchant']);
        return view('customer-bills.show', compact('customerBill'));
    }

    /**
     * Show the form for editing the specified customer bill.
     */
    public function edit(CustomerBill $customerBill): View
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $customerBill->load(['items.product', 'items.merchantBillItem.merchantBill.merchant']);
        return view('customer-bills.edit', compact('customerBill', 'customers'));
    }

    /**
     * Update the specified customer bill.
     */
    public function update(Request $request, CustomerBill $customerBill): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'bill_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.misc_adjustment' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($validated, $customerBill) {
            // Store old data for logging
            $oldData = [
                'customer_id' => $customerBill->customer_id,
                'bill_date' => $customerBill->bill_date->format('Y-m-d'),
                'total_amount' => $customerBill->total_amount,
                'notes' => $customerBill->notes,
                'items' => $customerBill->items->toArray(),
            ];

            $customerBill->update([
                'customer_id' => $validated['customer_id'],
                'bill_date' => $validated['bill_date'],
                'notes' => $validated['notes'],
            ]);

            // Delete existing items and re-create
            $customerBill->items()->delete();

            $grandTotal = 0;
            $newItems = [];
            foreach ($validated['items'] as $itemData) {
                $quantity = $itemData['quantity'];
                $rate = $itemData['rate'];
                $miscAdjustment = $itemData['misc_adjustment'] ?? 0;
                $netQuantity = $quantity - $miscAdjustment;
                $total = $netQuantity * $rate;

                $item = [
                    'merchant_bill_item_id' => null, // Manual edit, no merchant bill item link
                    'product_id' => $itemData['product_id'],
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'misc_adjustment' => $miscAdjustment,
                    'net_quantity' => $netQuantity,
                    'total_amount' => $total,
                ];

                $customerBill->items()->create($item);
                $newItems[] = $item;
                $grandTotal += $total;
            }

            $customerBill->update(['total_amount' => $grandTotal]);

            // Store new data for logging
            $newData = [
                'customer_id' => $customerBill->customer_id,
                'bill_date' => $customerBill->bill_date->format('Y-m-d'),
                'total_amount' => $customerBill->total_amount,
                'notes' => $customerBill->notes,
                'items' => $newItems,
            ];

            // Log the update
            $changesSummary = BillEditLogService::generateChangesSummary($oldData, $newData);
            BillEditLogService::logCustomerBillUpdated($customerBill->id, $oldData, $newData, $changesSummary);
        });

        return redirect()->route('customer-bills.index')
            ->with('success', 'Customer Bill updated successfully!');
    }

    /**
     * Remove the specified customer bill.
     */
    public function destroy(CustomerBill $customerBill): RedirectResponse
    {
        // Store data for logging before deletion
        $billData = [
            'customer_id' => $customerBill->customer_id,
            'bill_date' => $customerBill->bill_date->format('Y-m-d'),
            'total_amount' => $customerBill->total_amount,
            'notes' => $customerBill->notes,
            'items_count' => $customerBill->items->count(),
        ];

        $customerBill->delete();

        // Log the deletion
        BillEditLogService::logCustomerBillDeleted($customerBill->id, $billData);

        return redirect()->route('customer-bills.index')
            ->with('success', 'Customer Bill deleted successfully!');
    }

    /**
     * Get customer's available products from merchant bills.
     */
    public function getCustomerProducts(Request $request)
    {
        try {
            $customerId = $request->input('customer_id');
            $billDate = $request->input('bill_date');
            
            if (!$customerId) {
                return response()->json(['error' => 'Customer ID is required'], 400);
            }
            
            // Build query for merchant bill items for this customer
            $query = MerchantBillItem::where('customer_id', $customerId)
                ->with(['product', 'merchantBill.merchant']);
            
            // Show ALL products for preview (not filtered by date)
            // The bill generation will include all products regardless of date
            
            $products = $query->orderBy('merchant_bill_id', 'desc') // Latest merchant bills first
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'product_unit' => $item->product->unit,
                        'quantity' => $item->quantity, // Original quantity
                        'rate' => $item->rate,
                        'misc_adjustment' => $item->misc_adjustment,
                        'net_quantity' => $item->net_quantity,
                        'total_amount' => $item->total_amount,
                        'merchant_name' => $item->merchantBill->merchant->name,
                        'merchant_bill_date' => $item->merchantBill->bill_date->format('Y-m-d'),
                        'merchant_bill_id' => $item->merchant_bill_id,
                    ];
                });

            return response()->json([
                'success' => true,
                'customer_id' => $customerId,
                'bill_date' => $billDate,
                'products_count' => $products->count(),
                'products' => $products
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getCustomerProducts: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error loading products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF for customer bill.
     */
    public function generatePdf(CustomerBill $customerBill)
    {
        $customerBill->load(['customer', 'items.product', 'items.merchantBillItem.merchantBill.merchant']);
        
        // Get current user details (business owner)
        $user = auth()->user();
        
        $pdf = Pdf::loadView('customer-bills.pdf', compact('customerBill', 'user'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download("customer-bill-{$customerBill->id}.pdf");
    }

    /**
     * Print customer bill (view PDF in browser).
     */
    public function print(CustomerBill $customerBill)
    {
        $customerBill->load(['customer', 'items.product', 'items.merchantBillItem.merchantBill.merchant']);
        
        // Get current user details (business owner)
        $user = auth()->user();
        
        $pdf = Pdf::loadView('customer-bills.pdf', compact('customerBill', 'user'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream("customer-bill-{$customerBill->id}.pdf");
    }
}
