<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'business_name',
        'gst_number',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
