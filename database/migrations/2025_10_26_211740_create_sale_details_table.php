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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();

            // Relación con la venta
            $table->foreignId('sale_id')
                ->constrained('sales')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Relación con el producto
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
                
            $table->foreignId('presentation_id')
                ->nullable()
                ->constrained('presentations')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->integer('quantity')->default(1);           // Cantidad del producto
            $table->decimal('price', 10, 2);                  // Precio unitario
            $table->decimal('discount', 10, 2)->default(0);   // Descuento aplicado al producto
            $table->decimal('subtotal', 10, 2);               // Subtotal (quantity * price - discount)

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
