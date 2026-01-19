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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('document_type')->nullable();    // Anulado, Aceptado
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('delivery_type_id')->nullable()->constrained('delivery_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('cash_summary_id')->nullable()->constrained('cash_summaries')->restrictOnDelete();

            $table->decimal('total_amount', 10, 2)->default(0); // Monto total
            $table->decimal('discount', 10, 2)->default(0);     // Descuento aplicado

            // Estado como string, por defecto "Pagado"
            $table->string('status')->default('Aceptado');

            $table->string('series', 10)->nullable();     // Serie del comprobante
            $table->integer('correlative')->nullable();  // Número correlativo

            $table->text('notes')->nullable(); // Notas adicionales
            $table->string('transaction_code', 50)->unique();
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
