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
        Schema::create('delivery_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');       // Nombre del tipo de entrega
            $table->decimal('extra_price', 8, 2)->default(0); // Precio extra asociado
            $table->boolean('is_active')->default(true); // Para activar o desactivar el tipo
            $table->timestamps();         // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_types');
    }
};
