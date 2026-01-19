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
        Schema::create('presentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('name');             // Nombre de la presentación (ej: "500ml", "Pack de 6")
            $table->decimal('price', 12, 2)->default(0);    // Precio de venta
            $table->integer('stock')->default(0); // Stock disponible
            $table->string('image')->nullable(); // Imagen de la presentación
            $table->string('is_active')->default('activo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presentations');
    }
};
