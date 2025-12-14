<?php

namespace App\Services;

use App\Models\CustomerBill;
use App\Models\CustomerBillItem;
use App\Models\MerchantBillItem;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerBillUpdateService
{
    /**
     * Round customer bill total: if decimal part >= 0.5, round up to next integer.
     * Example: 632.5 -> 633, 632.4 -> 632
     */
    public static function roundCustomerBillTotal($total)
    {
        $decimal = $total - floor($total);
        if ($decimal >= 0.5) {
            return ceil($total);
        }
        return floor($total);
    }

    /**
     * Update all customer bills when a merchant bill is edited.
     */
    public static function updateCustomerBillsFromMerchantBill($merchantBill, $oldItems, $newItemsData)
    {
        $updateSummary = [
            'updated_customer_bills' => [],
            'balance_adjustments' => [],
            'items_added' => 0,
            'items_updated' => 0,
            'items_removed' => 0,
        ];

        DB::transaction(function () use ($merchantBill, $oldItems, $newItemsData, &$updateSummary) {
            // Get all customer bills that reference items from this merchant bill
            $affectedCustomerBills = CustomerBillItem::whereIn('merchant_bill_item_id', $oldItems->pluck('id'))
                ->with(['customerBill.customer'])
                ->get()
                ->groupBy('customer_bill_id');

            foreach ($affectedCustomerBills as $customerBillId => $customerBillItems) {
                $customerBill = $customerBillItems->first()->customerBill;
                $customer = $customerBill->customer;
                
                $oldTotal = $customerBill->total_amount;
                $balanceAdjustment = 0;

                // Delete all existing customer bill items for this merchant bill
                foreach ($customerBillItems as $customerBillItem) {
                    $balanceAdjustment -= $customerBillItem->total_amount;
                    $customerBillItem->delete();
                    $updateSummary['items_removed']++;
                }

                // Add new items from the updated merchant bill data
                $newItemsForThisCustomer = $newItemsData->where('customer_id', $customerBill->customer_id);
                
                foreach ($newItemsForThisCustomer as $newItemData) {
                    // Create a temporary merchant bill item to get an ID
                    $tempMerchantItem = $merchantBill->items()->create($newItemData);
                    
                    $customerBill->items()->create([
                        'merchant_bill_item_id' => $tempMerchantItem->id,
                        'product_id' => $newItemData['product_id'],
                        'quantity' => $newItemData['quantity'],
                        'weight' => $newItemData['weight'],
                        'rate' => $newItemData['rate'],
                        'misc_adjustment' => $newItemData['misc_adjustment'],
                        'net_quantity' => $newItemData['net_quantity'],
                        'total_amount' => $newItemData['total_amount'],
                    ]);
                    
                    $balanceAdjustment += $newItemData['total_amount'];
                    $updateSummary['items_added']++;
                }

                // Recalculate customer bill total and round up if decimal >= 0.5
                $newTotal = $customerBill->items()->sum('total_amount');
                $roundedTotal = self::roundCustomerBillTotal($newTotal);
                $customerBill->update(['total_amount' => $roundedTotal]);

                // Update customer balance
                if ($balanceAdjustment != 0) {
                    $customer->updateBalance($balanceAdjustment, 'bill');
                }

                // Store update summary
                $updateSummary['updated_customer_bills'][] = [
                    'customer_bill_id' => $customerBill->id,
                    'customer_name' => $customer->name,
                    'old_total' => $oldTotal,
                    'new_total' => $roundedTotal,
                    'balance_adjustment' => $balanceAdjustment,
                ];

                $updateSummary['balance_adjustments'][] = [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'adjustment' => $balanceAdjustment,
                ];

                // Log the customer bill update
                Log::info('Customer bill updated due to merchant bill edit', [
                    'merchant_bill_id' => $merchantBill->id,
                    'customer_bill_id' => $customerBill->id,
                    'customer_name' => $customer->name,
                    'old_total' => $oldTotal,
                    'new_total' => $roundedTotal,
                    'balance_adjustment' => $balanceAdjustment,
                ]);
            }
        });

        return $updateSummary;
    }

    /**
     * Get a summary of changes for user feedback.
     */
    public static function generateUpdateSummary($updateSummary)
    {
        $summary = [];
        
        if ($updateSummary['items_added'] > 0) {
            $summary[] = "Added {$updateSummary['items_added']} new items";
        }
        
        if ($updateSummary['items_updated'] > 0) {
            $summary[] = "Updated {$updateSummary['items_updated']} existing items";
        }
        
        if ($updateSummary['items_removed'] > 0) {
            $summary[] = "Removed {$updateSummary['items_removed']} items";
        }
        
        if (count($updateSummary['updated_customer_bills']) > 0) {
            $summary[] = "Updated " . count($updateSummary['updated_customer_bills']) . " customer bills";
        }
        
        return implode(', ', $summary);
    }
}