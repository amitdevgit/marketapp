<?php

namespace App\Services;

use App\Models\BillEditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillEditLogService
{
    /**
     * Log a bill edit action.
     */
    public static function log(string $billType, int $billId, string $action, array $oldData = null, array $newData = null, string $changesSummary = null): void
    {
        $user = Auth::user();
        $request = request();

        BillEditLog::create([
            'bill_type' => $billType,
            'bill_id' => $billId,
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'changes_summary' => $changesSummary,
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
        ]);
    }

    /**
     * Log merchant bill creation.
     */
    public static function logMerchantBillCreated(int $billId, array $billData): void
    {
        self::log(
            'merchant_bill',
            $billId,
            'created',
            null,
            $billData,
            'Merchant bill created'
        );
    }

    /**
     * Log merchant bill update.
     */
    public static function logMerchantBillUpdated(int $billId, array $oldData, array $newData, string $changesSummary): void
    {
        self::log(
            'merchant_bill',
            $billId,
            'updated',
            $oldData,
            $newData,
            $changesSummary
        );
    }

    /**
     * Log merchant bill deletion.
     */
    public static function logMerchantBillDeleted(int $billId, array $billData): void
    {
        self::log(
            'merchant_bill',
            $billId,
            'deleted',
            $billData,
            null,
            'Merchant bill deleted'
        );
    }

    /**
     * Log customer bill creation.
     */
    public static function logCustomerBillCreated(int $billId, array $billData): void
    {
        self::log(
            'customer_bill',
            $billId,
            'created',
            null,
            $billData,
            'Customer bill created'
        );
    }

    /**
     * Log customer bill update.
     */
    public static function logCustomerBillUpdated(int $billId, array $oldData, array $newData, string $changesSummary): void
    {
        self::log(
            'customer_bill',
            $billId,
            'updated',
            $oldData,
            $newData,
            $changesSummary
        );
    }

    /**
     * Log customer bill deletion.
     */
    public static function logCustomerBillDeleted(int $billId, array $billData): void
    {
        self::log(
            'customer_bill',
            $billId,
            'deleted',
            $billData,
            null,
            'Customer bill deleted'
        );
    }

    /**
     * Generate a human-readable summary of changes.
     */
    public static function generateChangesSummary(array $oldData, array $newData): string
    {
        $changes = [];
        
        // Compare basic fields
        $fieldsToCompare = ['merchant_id', 'customer_id', 'bill_date', 'total_amount', 'notes'];
        
        foreach ($fieldsToCompare as $field) {
            if (isset($oldData[$field]) && isset($newData[$field])) {
                if ($oldData[$field] != $newData[$field]) {
                    $changes[] = ucfirst(str_replace('_', ' ', $field)) . " changed from '{$oldData[$field]}' to '{$newData[$field]}'";
                }
            }
        }

        // Compare items if they exist
        if (isset($oldData['items']) && isset($newData['items'])) {
            $oldItemsCount = count($oldData['items']);
            $newItemsCount = count($newData['items']);
            
            if ($oldItemsCount != $newItemsCount) {
                $changes[] = "Items count changed from {$oldItemsCount} to {$newItemsCount}";
            }
        }

        return empty($changes) ? 'No significant changes detected' : implode(', ', $changes);
    }
}
