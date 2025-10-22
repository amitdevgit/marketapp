<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'product_type',
        'unit',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
