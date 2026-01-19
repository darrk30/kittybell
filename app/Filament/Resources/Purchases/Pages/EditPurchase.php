<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Enums\StatePayment;
use App\Enums\StatePurchase;
use App\Enums\StateReceiving;
use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\MovementStock;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $purchase = $this->record;

        // Si es cotización, no tocar stock
        if ($purchase->document_type->value === 'Cotización') {
            return;
        }

        // ⚙️ Si la compra está anulada → revertir stock y movimientos
        if ($purchase->status->value === StatePurchase::Anulado->value) {
            foreach ($purchase->details as $detail) {
                // Revertir stock
                $detail->product->decrement('stock', $detail->quantity);
            }

            // Eliminar movimientos relacionados
            MovementStock::where('purchase_id', $purchase->id)->delete();

            return;
        }

        // Si no está recibido, no tocar stock
        if (! $purchase->receiving_status || $purchase->receiving_status->value !== StateReceiving::Recibido->value) {
            return;
        }

        // Crear movimientos si no existen y actualizar stock
        foreach ($purchase->details as $detail) {
            $exists = MovementStock::where('purchase_id', $purchase->id)
                ->where('product_id', $detail->product_id)
                ->exists();

            if (! $exists) {
                MovementStock::create([
                    'product_id'          => $detail->product_id,
                    'quantity'            => $detail->quantity,
                    'movement_type'       => 'compra',
                    'sale_id'             => null,
                    'purchase_id'         => $purchase->id,
                    'adjustment_stock_id' => null,
                    'balance'             => $detail->product->stock + $detail->quantity,
                ]);

                // Actualizar stock real
                $detail->product->increment('stock', $detail->quantity);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
