<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerBill extends Model
{
    protected $fillable = [
        'customer_id',
        'bill_date',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CustomerBillItem::class);
    }
}