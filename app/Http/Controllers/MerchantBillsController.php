<?php

namespace App\Http\Controllers;

use App\Models\MerchantBill;
use App\Models\MerchantBillItem;
use App\Models\CustomerBill;
use App\Models\CustomerBillItem;
use App\Models\Merchant;
use App\Models\Customer;
use App\Models\Product;
use App\Services\CustomerBillUpdateService;
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
            'items.*.weight' => 'required|numeric|min:0',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.misc_adjustment' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($validated) {
            // Calculate net quantity and total amount for each item
            $items = collect($validated['items'])->map(function ($item) {
                $netQuantity = $item['weight'] - ($item['misc_adjustment'] ?? 0); // Net Qty = Weight - Misc Adj
                $totalAmount = $netQuantity * $item['rate']; // Total = Net Qty × Rate
                
                return [
                    'customer_id' => $item['customer_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'weight' => $item['weight'],
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
        
        $merchantBill->load(['items.customer', 'items.product']);
        
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
            'items.*.weight' => 'required|numeric|min:0',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.misc_adjustment' => 'nullable|numeric',
        ]);

        $updateSummary = null;
        
        DB::transaction(function () use ($validated, $merchantBill, &$updateSummary) {
            // Store old data for logging and customer bill updates
            $oldData = [
                'merchant_id' => $merchantBill->merchant_id,
                'bill_date' => $merchantBill->bill_date->format('Y-m-d'),
                'total_amount' => $merchantBill->total_amount,
                'notes' => $merchantBill->notes,
                'items' => $merchantBill->items->toArray(),
            ];
            
            // Capture old items BEFORE deleting them
            $oldItems = $merchantBill->items;

            // Calculate net quantity and total amount for each item
            $items = collect($validated['items'])->map(function ($item) {
                $netQuantity = $item['weight'] - ($item['misc_adjustment'] ?? 0); // Net Qty = Weight - Misc Adj
                $totalAmount = $netQuantity * $item['rate']; // Total = Net Qty × Rate
                
                return [
                    'customer_id' => $item['customer_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'weight' => $item['weight'],
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

            // Automatically update all related customer bills BEFORE deleting merchant bill items
            $updateSummary = CustomerBillUpdateService::updateCustomerBillsFromMerchantBill(
                $merchantBill, 
                $oldItems, 
                collect($items)
            );

            // Delete existing items and create new ones AFTER updating customer bills
            $merchantBill->items()->delete();
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
                'customer_bill_updates' => $updateSummary,
            ];

            // Log the update with customer bill update information
            $changesSummary = BillEditLogService::generateChangesSummary($oldData, $newData);
            $changesSummary .= ' | Customer Bills: ' . CustomerBillUpdateService::generateUpdateSummary($updateSummary);
            BillEditLogService::logMerchantBillUpdated($merchantBill->id, $oldData, $newData, $changesSummary);
        });

        // Generate success message with customer bill update information
        $successMessage = 'Merchant bill updated successfully!';
        if ($updateSummary && count($updateSummary['updated_customer_bills']) > 0) {
            $customerBillSummary = CustomerBillUpdateService::generateUpdateSummary($updateSummary);
            $successMessage .= ' ' . $customerBillSummary . '.';
        }

        return redirect()->route('merchant-bills.index')
            ->with('success', $successMessage);
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