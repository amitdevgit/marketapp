<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerBillItem extends Model
{
    protected $fillable = [
        'customer_bill_id',
        'merchant_bill_item_id',
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

    public function customerBill(): BelongsTo
    {
        return $this->belongsTo(CustomerBill::class);
    }

    public function merchantBillItem(): BelongsTo
    {
        return $this->belongsTo(MerchantBillItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}