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
            
            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('email')->unique();
            
            // Document Number (CPF)
            $table->string('document_number', 11)->unique();
            
            // ID Card (RG)
            $table->string('id_card', 20)->nullable();
            $table->string('id_card_issuer', 10)->nullable(); // Órgão Emissor
            $table->char('id_card_state', 2)->nullable();     // UF do RG
            
            $table->string('phone');

            // Address
            $table->string('zip_code', 8);
            $table->string('address');
            $table->string('address_number'); // Alterado para evitar conflito com palavra reservada
            $table->string('neighborhood');
            $table->string('city');
            $table->char('state', 2);
            $table->string('complement')->nullable();

            // System / Financial
            $table->boolean('is_active')->default(true);
            $table->integer('due_day')->default(5);
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