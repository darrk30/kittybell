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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del producto
            $table->string('slug')->unique(); // Nombre del producto
            $table->text('description')->nullable(); // Descripción opcional
            $table->decimal('price', 12, 2);    // Precio de venta
            $table->decimal('cost', 12, 2);    // Precio de compra
            $table->integer('stock')->default(0); // Stock disponible
            $table->string('image')->nullable(); // Ruta o nombre del archivo de imagen
            $table->string('is_active')->default('activo');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
