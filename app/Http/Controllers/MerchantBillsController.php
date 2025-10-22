<?php

namespace App\Http\Controllers;

use App\Models\MerchantBill;
use App\Models\MerchantBillItem;
use App\Models\CustomerBill;
use App\Models\CustomerBillItem;
use App\Models\Merchant;
use App\Models\Customer;
use App\Models\Product;
use App\Services\BillEditLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class MerchantBillsController extends Controller
{
    /**
     * Display a listing of merchant bills.
     */
    public function index(): View
    {
        $merchantBills = MerchantBill::with(['merchant', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('merchant-bills.index', compact('merchantBills'));
    }

    /**
     * Show the form for creating a new merchant bill.
     */
    public function create(): View
    {
        $merchants = Merchant::where('is_active', true)->get();
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        
        return view('merchant-bills.create', compact('merchants', 'customers', 'products'));
    }

    /**
     * Store a newly created merchant bill.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'bill_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.customer_id' => 'required|exists:customers,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.misc_adjustment' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($validated) {
            // Calculate net quantity and total amount for each item
            $items = collect($validated['items'])->map(function ($item) {
                $netQuantity = $item['quantity'] - ($item['misc_adjustment'] ?? 0); // Changed from + to -
                $totalAmount = $netQuantity * $item['rate'];
                
                return [
                    'customer_id' => $item['customer_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'rate' => $item['rate'],
                    'misc_adjustment' => $item['misc_adjustment'] ?? 0,
                    'net_quantity' => $netQuantity,
                    'total_amount' => $totalAmount,
                ];
            });

            // Calculate total amount
            $totalAmount = $items->sum('total_amount');

                // Create merchant bill
                $merchantBill = MerchantBill::create([
                    'merchant_id' => $validated['merchant_id'],
                    'bill_date' => $validated['bill_date'],
                    'total_amount' => $totalAmount,
                    'notes' => $validated['notes'],
                ]);

                // Create bill items
                foreach ($items as $item) {
                    $merchantBill->items()->create($item);
                }

                // Log the creation
                BillEditLogService::logMerchantBillCreated($merchantBill->id, [
                    'merchant_id' => $merchantBill->merchant_id,
                    'bill_date' => $merchantBill->bill_date->format('Y-m-d'),
                    'total_amount' => $merchantBill->total_amount,
                    'notes' => $merchantBill->notes,
                    'items_count' => count($items),
                ]);
        });

        return redirect()->route('merchant-bills.index')
            ->with('success', 'Merchant bill created successfully!');
    }

    /**
     * Display the specified merchant bill.
     */
    public function show(MerchantBill $merchantBill): View
    {
        $merchantBill->load(['merchant', 'items.customer', 'items.product']);
        return view('merchant-bills.show', compact('merchantBill'));
    }

    /**
     * Show the form for editing the specified merchant bill.
     */
    public function edit(MerchantBill $merchantBill): View
    {
        $merchants = Merchant::where('is_active', true)->get();
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        
        $merchantBill->load('items');
        
        return view('merchant-bills.edit', compact('merchantBill', 'merchants', 'customers', 'products'));
    }

    /**
     * Update the specified merchant bill.
     */
    public function update(Request $request, MerchantBill $merchantBill): RedirectResponse
    {
        $validated = $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'bill_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.customer_id' => 'required|exists:customers,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.misc_adjustment' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($validated, $merchantBill) {
            // Store old data for logging
            $oldData = [
                'merchant_id' => $merchantBill->merchant_id,
                'bill_date' => $merchantBill->bill_date->format('Y-m-d'),
                'total_amount' => $merchantBill->total_amount,
                'notes' => $merchantBill->notes,
                'items' => $merchantBill->items->toArray(),
            ];

            // Delete existing items
            $merchantBill->items()->delete();

            // Calculate net quantity and total amount for each item
            $items = collect($validated['items'])->map(function ($item) {
                $netQuantity = $item['quantity'] - ($item['misc_adjustment'] ?? 0); // Changed from + to -
                $totalAmount = $netQuantity * $item['rate'];
                
                return [
                    'customer_id' => $item['customer_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'rate' => $item['rate'],
                    'misc_adjustment' => $item['misc_adjustment'] ?? 0,
                    'net_quantity' => $netQuantity,
                    'total_amount' => $totalAmount,
                ];
            });

            // Calculate total amount
            $totalAmount = $items->sum('total_amount');

            // Update merchant bill
            $merchantBill->update([
                'merchant_id' => $validated['merchant_id'],
                'bill_date' => $validated['bill_date'],
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'],
            ]);

            // Create new bill items
            foreach ($items as $item) {
                $merchantBill->items()->create($item);
            }

            // Store new data for logging
            $newData = [
                'merchant_id' => $merchantBill->merchant_id,
                'bill_date' => $merchantBill->bill_date->format('Y-m-d'),
                'total_amount' => $merchantBill->total_amount,
                'notes' => $merchantBill->notes,
                'items' => $items->toArray(),
            ];

            // Log the update
            $changesSummary = BillEditLogService::generateChangesSummary($oldData, $newData);
            BillEditLogService::logMerchantBillUpdated($merchantBill->id, $oldData, $newData, $changesSummary);
        });

        return redirect()->route('merchant-bills.index')
            ->with('success', 'Merchant bill updated successfully!');
    }

    /**
     * Remove the specified merchant bill.
     */
    public function destroy(MerchantBill $merchantBill): RedirectResponse
    {
        // Store data for logging before deletion
        $billData = [
            'merchant_id' => $merchantBill->merchant_id,
            'bill_date' => $merchantBill->bill_date->format('Y-m-d'),
            'total_amount' => $merchantBill->total_amount,
            'notes' => $merchantBill->notes,
            'items_count' => $merchantBill->items->count(),
        ];

        $merchantBill->delete();

        // Log the deletion
        BillEditLogService::logMerchantBillDeleted($merchantBill->id, $billData);

        return redirect()->route('merchant-bills.index')
            ->with('success', 'Merchant bill deleted successfully!');
    }

    /**
     * Generate customer bills from merchant bill.
     */
    public function generateCustomerBills(MerchantBill $merchantBill): RedirectResponse
    {
        DB::transaction(function () use ($merchantBill) {
            // Group items by customer
            $itemsByCustomer = $merchantBill->items->groupBy('customer_id');

            foreach ($itemsByCustomer as $customerId => $items) {
                // Find or create ONE customer bill for this customer and date
                $customerBill = CustomerBill::firstOrCreate(
                    [
                        'customer_id' => $customerId,
                        'bill_date' => $merchantBill->bill_date,
                    ],
                    [
                        'total_amount' => 0,
                        'notes' => 'Generated from merchant bill #' . $merchantBill->id,
                    ]
                );

                // Avoid duplicates if this merchant bill was processed before
                $existingLinkedItemIds = CustomerBillItem::where('customer_bill_id', $customerBill->id)
                    ->pluck('merchant_bill_item_id')
                    ->filter()
                    ->toArray();

                // Create customer bill items (preserve original qty and adjustments)
                foreach ($items as $item) {
                    if (in_array($item->id, $existingLinkedItemIds, true)) {
                        continue; // skip already-added merchant bill item
                    }
                    $customerBill->items()->create([
                        'merchant_bill_item_id' => $item->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity, // original quantity from merchant item
                        'rate' => $item->rate,
                        'misc_adjustment' => $item->misc_adjustment ?? 0,
                        'net_quantity' => $item->net_quantity,
                        'total_amount' => $item->total_amount,
                    ]);
                }

                // Recompute total to reflect all items in this consolidated bill
                $recalculatedTotal = $customerBill->items()->sum('total_amount');
                $customerBill->update(['total_amount' => $recalculatedTotal]);
            }
        });

        return redirect()->route('merchant-bills.index')
            ->with('success', 'Customer bills generated successfully!');
    }
}