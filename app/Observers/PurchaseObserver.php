<?php

namespace App\Observers;

use App\Enums\StatePayment;
use App\Enums\StatePurchase;
use App\Models\CashSummary;
use App\Models\Purchase;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class PurchaseObserver
{
    public function creating(Purchase $purchase): void
    {
        // 🔹 Si es cotización, código temporal
        if ($purchase->document_type->value === 'Cotización') {
            $purchase->transaction_code = 'TEMP-' . now()->format('YmdHis');
        } else {
            $purchase->transaction_code = self::generateTransactionCode($purchase);
        }
    }

    public function created(Purchase $purchase): void
    {
        // ⚙️ Registrar transacción solo si está pagado
        if ($purchase->payment_status && $purchase->payment_status->value === StatePayment::Pagado->value) {
            self::createTransaction($purchase);
        }
    }

    public function updated(Purchase $purchase): void
    {
        try {
            // 🔹 Si la compra fue ANULADA, revertir transacción
            if ($purchase->status->value === StatePurchase::Anulado->value) {
                self::revertTransaction($purchase);
                return;
            }

            // 🧠 Detectar si cambió de cotización a compra
            if ($purchase->document_type->value !== 'Cotización') {
                // 🔹 Generar código real si aún es temporal
                if (str_starts_with($purchase->transaction_code, 'TEMP-')) {
                    $purchase->transaction_code = self::generateTransactionCode($purchase);
                    $purchase->saveQuietly(); // evitar bucle
                }

                // 🔹 Crear transacción si está pagado
                if ($purchase->payment_status->value === StatePayment::Pagado->value) {
                    self::createTransaction($purchase);
                }
            }

            // ⚙️ También manejar caso cuando cambia el estado de pago
            if ($purchase->payment_status->value === StatePayment::Pagado->value) {

                // Si no tiene transacción aún, crearla
                $hasTransaction = Transaction::where('transactionable_id', $purchase->id)
                    ->where('transactionable_type', Purchase::class)
                    ->exists();

                if (! $hasTransaction) {
                    self::createTransaction($purchase);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error en updated PurchaseObserver: ' . $e->getMessage());
        }
    }

    // 🧩 Revertir transacción si la compra se anuló
    private static function revertTransaction(Purchase $purchase): void
    {
        $transaction = Transaction::where('transactionable_id', $purchase->id)
            ->where('transactionable_type', Purchase::class)
            ->first();

        if (! $transaction) {
            return;
        }

        // 🔹 Devolver dinero a la caja
        if ($cashSummary = CashSummary::find($transaction->cash_summary_id)) {
            $cashSummary->current_balance += $transaction->amount;
            $cashSummary->save();
        }

        // 🔹 Eliminar transacción
        $transaction->delete();
    }

    // 🧩 Generar código único
    private static function generateTransactionCode(Purchase $purchase): string
    {
        $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 3));
        return sprintf('%s-%05d-%s', $purchase->series, $purchase->correlative, $random);
    }

    // 🧩 Crear transacción
    private static function createTransaction(Purchase $purchase): void
    {
        $transaction = Transaction::create([
            'amount' => $purchase->total_amount,
            'transaction_type' => 'Egreso',
            'description' => "Compra {$purchase->series}-{$purchase->correlative}",
            'cash_summary_id' => $purchase->cash_summary_id,
            'transaction_code' => $purchase->transaction_code,
            'transactionable_id' => $purchase->id,
            'transactionable_type' => Purchase::class,
        ]);

        // 🔹 Actualizar saldo de caja
        if ($cashSummary = CashSummary::find($transaction->cash_summary_id)) {
            $cashSummary->current_balance -= $transaction->amount;
            $cashSummary->save();
        }
    }
}
