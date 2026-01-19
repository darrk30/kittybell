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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);          // Nombre del tipo de documento (ej. DNI, RUC)
            $table->string('code', 10)->nullable()->unique(); // Código corto opcional
            $table->unsignedTinyInteger('max_length'); // Máximo de caracteres permitido
            $table->boolean('is_active')->default(true); // Activo o inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
