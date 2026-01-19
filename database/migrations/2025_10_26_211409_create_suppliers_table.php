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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            $table->string('name');                    // Nombre del proveedor
            $table->foreignId('document_type_id')      // Tipo de documento
                ->nullable()
                ->constrained('document_types')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('document_number')->nullable()->unique(); // Número de documento

            $table->string('email')->nullable()->unique(); // Correo electrónico
            $table->string('phone')->nullable();          // Teléfono
            $table->string('address')->nullable();        // Dirección
            $table->boolean('is_active')->default(true);  // Estado activo/inactivo
            $table->timestamps();                         // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
