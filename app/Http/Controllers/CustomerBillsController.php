<?php

namespace App\Http\Controllers;

use App\Models\CustomerBill;
use App\Models\CustomerBillItem;
use App\Models\MerchantBillItem;
use App\Models\Customer;
use App\Models\Product;
use App\Services\BillEditLogService;
use App\Services\CustomerBillUpdateService;
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
        // Get all customer bills for DataTables (it handles pagination)
        $customerBills = CustomerBill::with(['customer', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();
        
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

            try {
                DB::transaction(function () use ($validated) {
                    $customerId = $validated['customer_id'];
                    $billDate = $validated['bill_date'];

                    // Check if there are any unbilled merchant items for this customer
                    $alreadyBilledItemIds = CustomerBillItem::whereHas('customerBill', function($query) use ($customerId) {
                        $query->where('customer_id', $customerId);
                    })->pluck('merchant_bill_item_id')->toArray();
                    
                    $unbilledMerchantItems = MerchantBillItem::where('customer_id', $customerId)
                        ->whereNotIn('id', $alreadyBilledItemIds)
                        ->with(['product', 'merchantBill.merchant'])
                        ->get();

                    if ($unbilledMerchantItems->isEmpty()) {
                        throw new \Exception('No unbilled products found for this customer. All merchant bill items have already been included in customer bills.');
                    }

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

                    // Prevent adding the same merchant items twice to this specific bill
                    $existingLinkedItemIds = $customerBill->items()
                        ->pluck('merchant_bill_item_id')
                        ->filter()
                        ->toArray();

                    $newItemsTotal = 0;
                    foreach ($unbilledMerchantItems as $merchantItem) {
                        if (in_array($merchantItem->id, $existingLinkedItemIds, true)) {
                            continue;
                        }

                        $customerBill->items()->create([
                            'merchant_bill_item_id' => $merchantItem->id,
                            'product_id' => $merchantItem->product_id,
                            'quantity' => $merchantItem->quantity,
                            'weight' => $merchantItem->weight,
                            'rate' => $merchantItem->rate,
                            'misc_adjustment' => $merchantItem->misc_adjustment,
                            'net_quantity' => $merchantItem->net_quantity,
                            'total_amount' => $merchantItem->total_amount,
                        ]);
                        
                        $newItemsTotal += $merchantItem->total_amount;
                    }

                    // Recalculate total and round up if decimal >= 0.5
                    $recalculatedTotal = $customerBill->items()->sum('total_amount');
                    $roundedTotal = CustomerBillUpdateService::roundCustomerBillTotal($recalculatedTotal);
                    $customerBill->update(['total_amount' => $roundedTotal]);

                    // Update customer balance with only the NEW amount
                    if ($newItemsTotal > 0) {
                        $customer = Customer::find($customerId);
                        $customer->updateBalance($newItemsTotal, 'bill');
                    }

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
                    
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $e->getMessage());
            }
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
