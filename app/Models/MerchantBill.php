<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerchantBill extends Model
{
    protected $fillable = [
        'merchant_id',
        'bill_date',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(MerchantBillItem::class);
    }
}