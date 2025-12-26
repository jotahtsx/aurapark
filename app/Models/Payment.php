<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $table = 'payments';
    
    protected $fillable = [
        'name',
        'is_active',
    ];

    // Isso garante que o Laravel sempre trate o campo como true/false no código
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
