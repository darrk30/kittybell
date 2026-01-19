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
        Schema::create('adjustment_stock_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('adjustment_stock_id')
                ->constrained('adjustment_stocks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            // Relación con el producto
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->integer('quantity');              // Cantidad ajustada
            $table->decimal('price', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment_detailstocks');
    }
};
