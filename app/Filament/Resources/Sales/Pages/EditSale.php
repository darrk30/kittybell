<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Enums\StateSale;
use App\Enums\StateReceiving;
use App\Filament\Resources\Sales\SaleResource;
use App\Models\MovementStock;
use App\Models\Transaction;
use App\Models\CashSummary;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $sale = $this->record;

        // 1️⃣ Si es cotización, no hacer nada
        if ($sale->document_type->value === 'Cotización') {
            return;
        }

        // 2️⃣ Si la venta está anulada → anular transacción, no tocar stock

        if ($sale->status->value === StateSale::Anulado->value) {
            // Buscar transacción y revertir
            $transaction = Transaction::where('transactionable_id', $sale->id)
                ->where('transactionable_type', $sale::class)
                ->first();

            if ($transaction && $cashSummary = CashSummary::find($transaction->cash_summary_id)) {
                $cashSummary->current_balance -= $transaction->amount;
                $cashSummary->save();
                $transaction->delete();
            }

            return; // No tocar stock
        }

        // 3️⃣ Si no hay detalles o no hay stock que mover, salir
        if (! $sale->details) return;

        // 4️⃣ Registrar movimientos de stock si no existen y actualizar stock
        foreach ($sale->details as $detail) {
            $exists = MovementStock::where('sale_id', $sale->id)
                ->where('product_id', $detail->product_id)
                ->exists();

            if (! $exists) {
                MovementStock::create([
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'movement_type' => 'venta',
                    'sale_id' => $sale->id,
                    'purchase_id' => null,
                    'adjustment_stock_id' => null,
                    'balance' => $detail->product->stock - $detail->quantity,
                ]);

                // Actualizar stock real
                $detail->product->decrement('stock', $detail->quantity);
            }
        }

        // 5️⃣ Registrar transacción si no existe
        if ($sale->cash_summary_id) {
            $existsTransaction = Transaction::where('transactionable_id', $sale->id)
                ->where('transactionable_type', $sale::class)
                ->exists();

            if (! $existsTransaction) {
                $transactionCode = $sale->transaction_code;

                Transaction::create([
                    'amount' => $sale->total_amount,
                    'transaction_type' => 'Ingreso',
                    'description' => "Venta {$sale->series}-{$sale->correlative}",
                    'cash_summary_id' => $sale->cash_summary_id,
                    'transaction_code' => $transactionCode,
                    'transactionable_id' => $sale->id,
                    'transactionable_type' => $sale::class,
                ]);

                // Actualizar balance
                $cashSummary = CashSummary::find($sale->cash_summary_id);
                if ($cashSummary) {
                    $cashSummary->current_balance += $sale->total_amount;
                    $cashSummary->save();
                }
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
