<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'monthly_fee' => 'decimal:2',
        'due_day' => 'integer',
    ];

    /**
     * ACCESSORS & MUTATORS
     */

    // Mapeia o atributo virtual 'cpf' para a coluna 'document_number'
    protected function cpf(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $doc = $attributes['document_number'] ?? null;
                if (!$doc) return null;
                return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $doc);
            },
            // Quando você fizer $model->cpf = '...'; ele salva na coluna certa
            set: fn($value) => ['document_number' => preg_replace('/\D/', '', $value)],
        );
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) return null;
                $value = preg_replace('/\D/', '', $value);
                if (strlen($value) === 11) {
                    return preg_replace("/(\d{2})(\d{5})(\d{4})/", "($1) $2-$3", $value);
                }
                return preg_replace("/(\d{2})(\d{4})(\d{4})/", "($1) $2-$3", $value);
            }
        );
    }

    protected function zipCode(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? preg_replace("/(\d{5})(\d{3})/", "$1-$2", $value) : null,
        );
    }
}
