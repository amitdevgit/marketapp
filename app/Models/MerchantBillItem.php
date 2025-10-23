<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchantBillItem extends Model
{
    protected $fillable = [
        'merchant_bill_id',
        'customer_id',
        'product_id',
        'quantity',
        'weight',
        'rate',
        'misc_adjustment',
        'net_quantity',
        'total_amount'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'weight' => 'decimal:2',
        'rate' => 'decimal:2',
        'misc_adjustment' => 'decimal:2',
        'net_quantity' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function merchantBill(): BelongsTo
    {
        return $this->belongsTo(MerchantBill::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}