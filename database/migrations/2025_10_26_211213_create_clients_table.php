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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           // Nombre del cliente
            $table->string('document_number')->unique();
            $table->string('email')->nullable()->unique();   // Correo electrónico
            $table->string('phone')->nullable();             // Teléfono del cliente
            $table->string('address')->nullable();           // Dirección del cliente
            $table->boolean('is_active')->default(true);     // Activo o inactivo
            $table->foreignId('document_type_id')->constrained('document_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
