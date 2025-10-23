<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_bill_id',
        'amount',
        'payment_method',
        'reference_number',
        'notes',
        'payment_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Get the customer that owns the payment.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the customer bill associated with the payment.
     */
    public function customerBill()
    {
        return $this->belongsTo(CustomerBill::class);
    }

    /**
     * Get payment method options.
     */
    public static function getPaymentMethods()
    {
        return [
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            'upi' => 'UPI',
            'card' => 'Card',
            'other' => 'Other',
        ];
    }

    /**
     * Get status options.
     */
    public static function getStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }
}
