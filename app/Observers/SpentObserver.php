<?php

namespace App\Observers;

use App\Models\CashSummary;
use App\Models\Spent;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class SpentObserver
{
    /**
     * Al crear un gasto, generar el codigo automáticamente.
     */
    public function creating(Spent $spent): void
    {
        // 🔹 Prefijo fijo para los gastos
        $series = 'GS01';

        // 🔹 Obtener el último ID o contador (por si no hay campo correlativo)
        $lastId = Spent::max('id') ?? 0;
        $nextNumber = $lastId + 1;

        // 🔹 Generar un código corto y único (ej: GS01-00023-A9F)
        $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 3)); // 3 caracteres hex

        $spent->transaction_code = sprintf(
            '%s-%05d-%s',
            $series,       // Serie fija de gastos
            $nextNumber,   // Incremental (relleno con ceros)
            $random        // Fragmento aleatorio
        );
    }

    /**
     * Handle the Spent "created" event.
     */
    public function created(Spent $spent): void
    {
        try {
            // Crear la transacción asociada al gasto
            $transaction = Transaction::create([
                'amount' => $spent->amount, // 💰 Monto del gasto
                'transaction_type' => 'Egreso', // 📤 Los gastos son egresos
                'description' => "Gasto {$spent->transaction_code}", // 🧾 Usa su código
                'cash_summary_id' => $spent->cash_summary_id, // Caja seleccionada (si existe)
                'transaction_code' => $spent->transaction_code, // 🔗 Mismo código que el gasto
                'transactionable_id' => $spent->id, // Polimórfica
                'transactionable_type' => Spent::class, // Polimórfica
            ]);

            // 🔹 Actualizar balance de la caja (restar el egreso)
            if ($transaction->cash_summary_id && $cashSummary = CashSummary::find($transaction->cash_summary_id)) {
                $cashSummary->current_balance -= $transaction->amount;
                $cashSummary->save();
            }
        } catch (\Exception $e) {
            Log::error('Error creando transacción desde gasto: ' . $e->getMessage());
        }
    }


    /**
     * Handle the Spent "updated" event.
     */
    public function updated(Spent $spent): void
    {
        try {
            $transaction = Transaction::where('transactionable_type', Spent::class)
                ->where('transactionable_id', $spent->id)
                ->first();

            if (! $transaction) {
                return; // No hay transacción, no se hace nada
            }

            // 🧮 Buscar la caja
            $cashSummary = CashSummary::find($transaction->cash_summary_id);

            if ($cashSummary) {
                // Diferencia entre el gasto anterior y el nuevo
                $originalAmount = $spent->getOriginal('amount');
                $newAmount = $spent->amount;
                $difference = $newAmount - $originalAmount;

                // Como es egreso, la diferencia positiva significa más gasto => restar
                $cashSummary->current_balance -= $difference;
                $cashSummary->save();
            }

            // 🔄 Actualizar la transacción asociada
            $transaction->update([
                'amount' => $spent->amount,
                'description' => "Gasto {$spent->transaction_code}",
            ]);
        } catch (\Exception $e) {
            Log::error('Error actualizando transacción desde gasto: ' . $e->getMessage());
        }
    }


    /**
     * Handle the Spent "deleted" event.
     */
    public function deleted(Spent $spent): void
    {
        //
    }

    /**
     * Handle the Spent "restored" event.
     */
    public function restored(Spent $spent): void
    {
        //
    }

    /**
     * Handle the Spent "force deleted" event.
     */
    public function forceDeleted(Spent $spent): void
    {
        //
    }
}
