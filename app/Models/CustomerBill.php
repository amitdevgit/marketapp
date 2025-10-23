<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'bill_date',
        'total_amount',
        'status',
        'notes',
        'payment_status',
        'paid_amount',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CustomerBillItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CustomerPayment::class);
    }

    /**
     * Get payment status options.
     */
    public static function getPaymentStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'partial' => 'Partial',
            'paid' => 'Paid',
        ];
    }

    /**
     * Update payment status based on paid amount.
     */
    public function updatePaymentStatus()
    {
        $totalPaid = $this->payments()->where('status', 'completed')->sum('amount');
        $this->paid_amount = $totalPaid;

        if ($totalPaid >= $this->total_amount) {
            $this->payment_status = 'paid';
        } elseif ($totalPaid > 0) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'pending';
        }

        $this->save();
    }

    /**
     * Get remaining amount to be paid.
     */
    public function getRemainingAmount()
    {
        return $this->total_amount - $this->paid_amount;
    }
}