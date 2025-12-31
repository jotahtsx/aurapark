<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'email',
        'document_number',
        'id_card',
        'id_card_issuer',
        'id_card_state',
        'phone',
        'zip_code',
        'address',
        'address_number',
        'neighborhood',
        'city',
        'state',
        'complement',
        'is_active',
        'due_day',
        'notes',
    ];
}
