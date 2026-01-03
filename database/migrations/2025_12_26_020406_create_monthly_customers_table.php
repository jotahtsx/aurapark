<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monthly_customers', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Personal Information
            |--------------------------------------------------------------------------
            */
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('email')->unique();

            /*
            |--------------------------------------------------------------------------
            | Documents
            |--------------------------------------------------------------------------
            */

            // CPF (11 digits, numbers only)
            $table->char('document_number', 11)->unique();

            // RG
            $table->string('id_card', 20)->nullable();
            $table->string('id_card_issuer', 10)->nullable(); // Órgão emissor
            $table->char('id_card_state', 2)->nullable();     // UF

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */
            $table->string('phone', 20);

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */
            $table->char('zip_code', 8);
            $table->string('address');
            $table->string('address_number');
            $table->string('neighborhood');
            $table->string('city');
            $table->char('state', 2);
            $table->string('complement')->nullable();

            /*
            |--------------------------------------------------------------------------
            | System / Financial
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')->default(true);

            // Day of month for billing (1–31)
            $table->unsignedTinyInteger('due_day')->default(5);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_customers');
    }
};
