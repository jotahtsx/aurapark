<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'cpf',
        'zip_code',
        'address',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'vehicle_model',
        'vehicle_plate',
        'vehicle_color',
        'monthly_fee',
        'due_day',
        'status',
    ];

    /**
     * Casting de tipos (opcional, mas ajuda na precisão do valor mensal)
     */
    protected $casts = [
        'monthly_fee' => 'decimal:2',
        'due_day' => 'integer',
    ];
}