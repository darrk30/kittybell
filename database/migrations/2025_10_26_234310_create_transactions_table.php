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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2);                  // Monto de la transacción
            $table->string('transaction_type'); // Tipo: ingreso, egreso, anulado
            $table->string('description')->nullable();
            $table->foreignId('cash_summary_id')->nullable()->constrained('cash_summaries')->nullOnDelete();
            $table->string('transaction_code', 50)->unique();
            $table->nullableMorphs('transactionable'); // 🔹 Polimórfico
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
