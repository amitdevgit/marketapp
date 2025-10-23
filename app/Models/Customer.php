<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'customer_type',
        'notes',
        'is_active',
        'balance',
        'total_purchased',
        'total_paid',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'balance' => 'decimal:2',
        'total_purchased' => 'decimal:2',
        'total_paid' => 'decimal:2',
    ];

    /**
     * Get the customer bills for the customer.
     */
    public function customerBills()
    {
        return $this->hasMany(CustomerBill::class);
    }

    /**
     * Get the payments for the customer.
     */
    public function payments()
    {
        return $this->hasMany(CustomerPayment::class);
    }

    /**
     * Get customer type options.
     */
    public static function getCustomerTypes()
    {
        return [
            'restaurant' => 'Restaurant',
            'retailer' => 'Retailer',
            'wholesaler' => 'Wholesaler',
            'other' => 'Other',
        ];
    }

    /**
     * Update customer balance after payment.
     */
    public function updateBalance($amount, $type = 'payment')
    {
        if ($type === 'payment') {
            $this->total_paid += $amount;
            $this->balance -= $amount;
        } elseif ($type === 'bill') {
            $this->total_purchased += $amount;
            $this->balance += $amount;
        }
        
        $this->save();
    }
}
