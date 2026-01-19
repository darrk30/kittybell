<?php

namespace App\Observers;

use App\Enums\StateSale;
use App\Models\CashSummary;
use App\Models\MovementStock;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class SaleObserver
{
    /**
     * Al crear una nueva venta, generar el correlativo automáticamente.
     */
    public function creating(Sale $sale): void
    {
        $lastCorrelative = Sale::where('series', $sale->series)->max('correlative') ?? 0;
        $sale->correlative = $lastCorrelative + 1;

        // 🔹 Generar transaction_code único
        do {
            $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 5)); // 5 caracteres hex
            $code = sprintf('%s-%05d-%s', $sale->series, $sale->correlative, $random);
            $exists = \App\Models\Transaction::where('transaction_code', $code)->exists();
        } while ($exists);

        $sale->transaction_code = $code;
    }

    /**
     * Handle the Sale "created" event.
     */
    public function created(Sale $sale): void
    {
        
    }



    /**
     * Handle the Sale "updated" event.
     */
    public function updated(Sale $sale): void {}

    /**
     * Handle the Sale "deleted" event.
     */
    public function deleted(Sale $sale): void
    {
        //
    }

    /**
     * Handle the Sale "restored" event.
     */
    public function restored(Sale $sale): void
    {
        //
    }

    /**
     * Handle the Sale "force deleted" event.
     */
    public function forceDeleted(Sale $sale): void
    {
        //
    }
}
