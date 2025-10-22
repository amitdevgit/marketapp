<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillEditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_type',
        'bill_id',
        'user_id',
        'action',
        'old_data',
        'new_data',
        'changes_summary',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    /**
     * Get the user who made the edit.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the merchant bill if this log is for a merchant bill.
     */
    public function merchantBill()
    {
        return $this->belongsTo(MerchantBill::class, 'bill_id')
            ->where('bill_type', 'merchant_bill');
    }

    /**
     * Get the customer bill if this log is for a customer bill.
     */
    public function customerBill()
    {
        return $this->belongsTo(CustomerBill::class, 'bill_id')
            ->where('bill_type', 'customer_bill');
    }

    /**
     * Scope to filter by bill type.
     */
    public function scopeByBillType($query, $billType)
    {
        return $query->where('bill_type', $billType);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }
}