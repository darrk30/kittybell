<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Enums\StateSale;
use App\Filament\Resources\Sales\SaleResource;
use App\Models\CashSummary;
use App\Models\MovementStock;
use App\Models\Sale;
use App\Models\Transaction;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function afterCreate(): void
    {
        $sale = $this->record;

        // 🔹 Si es cotización o ya está anulado, no hacer nada
        if ($sale->document_type->value === 'Cotización' || $sale->status->value === StateSale::Anulado->value) {
            return;
        }

        foreach ($sale->details as $detail) {
            $exists = MovementStock::where('sale_id', $sale->id)
                ->where('product_id', $detail->product_id)
                ->exists();

            if (! $exists) {
                MovementStock::create([
                    'product_id'     => $detail->product_id,
                    'quantity'            => $detail->quantity,
                    'movement_type'       => 'venta',
                    'sale_id'             => $sale->id,
                    'purchase_id'         => null,
                    'adjustment_stock_id' => null,
                    'balance'             => $detail->product->stock - $detail->quantity,
                ]);

                // Actualizar stock real
                $detail->product->decrement('stock', $detail->quantity);
            }
        }

        // Registrar transacción
        if ($sale->cash_summary_id) {
            $transaction = Transaction::create([
                'amount' => $sale->total_amount,
                'transaction_type' => 'Ingreso',
                'description' => "Venta {$sale->series}-{$sale->correlative}",
                'cash_summary_id' => $sale->cash_summary_id,
                'transaction_code' => $sale->transaction_code,
                'transactionable_id' => $sale->id,
                'transactionable_type' => Sale::class,
            ]);

            // Actualizar balance
            $cashSummary = CashSummary::find($transaction->cash_summary_id);
            if ($cashSummary) {
                $cashSummary->current_balance += $transaction->amount;
                $cashSummary->save();
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
