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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();

            // Relación con la compra
            $table->foreignId('purchase_id')
                ->constrained('purchases')
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

            $table->integer('quantity')->default(1);           // Cantidad comprada
            $table->decimal('cost', 12, 2);                  // Precio unitario de compra
            $table->decimal('discount', 12, 2)->default(0);   // Descuento aplicado al producto
            $table->decimal('subtotal', 12, 2);               // Subtotal (quantity * price - discount)

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
