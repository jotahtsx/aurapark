<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $fillable = [
        'category',
        'hourly_price',
        'monthly_price',
        'total_spots',
        'is_active',
    ];

    protected $casts = [
        'hourly_price' => 'decimal:2',
        'monthly_price' => 'decimal:2',
        'total_spots' => 'integer',
        'is_active' => 'boolean',
    ];
}
