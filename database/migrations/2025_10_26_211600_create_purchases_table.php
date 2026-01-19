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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('document_type')->nullable();    // Anulado, Aceptado
            // Relación con proveedor
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->restrictOnDelete();
            $table->foreignId('cash_summary_id')->nullable()->constrained('cash_summaries')->restrictOnDelete();

            $table->date('purchase_date')->nullable();         // Fecha de la compra
            $table->decimal('total_amount', 12, 2)->default(0); // Monto total
            $table->decimal('discount', 12, 2)->default(0);     // Descuento aplicado

            // Estados separados
            $table->string('payment_status')->nullable();     // Pendiente, Pagado, Cancelado
            $table->string('receiving_status')->nullable(); // Por recibir, Parcial, Recibido

            $table->string('series', 10)->nullable();    // Serie del comprobante
            $table->string('status')->nullable();    // Anulado, Aceptado
            $table->integer('correlative')->nullable(); // Número correlativo
            $table->text('notes')->nullable();          // Notas adicionales
            $table->string('transaction_code', 50)->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
