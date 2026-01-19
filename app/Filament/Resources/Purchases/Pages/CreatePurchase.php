<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Enums\StateReceiving;
use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\MovementStock;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function afterCreate(): void
    {
        $purchase = $this->record;

        // ⚠️ Si es cotización, no mover stock
        if ($purchase->document_type->value === 'Cotización') {
            return;
        }

        // ⚠️ Si no tiene estado de recepción o no está recibido, tampoco
        if (
            ! $purchase->receiving_status ||
            $purchase->receiving_status->value !== StateReceiving::Recibido->value
        ) {
            return;
        }

        foreach ($purchase->details as $detail) {
            // Evitar duplicar movimientos
            $exists = MovementStock::where('product_id', $purchase->id)
                ->where('product_id', $detail->product_id)
                ->exists();

            if (! $exists) {
                MovementStock::create([
                    'product_id'     => $detail->product_id,
                    'quantity'            => $detail->quantity,
                    'movement_type'       => 'compra',
                    'sale_id'             => null,
                    'purchase_id'         => $purchase->id,
                    'adjustment_stock_id' => null,
                    'balance'             => $detail->product->stock + $detail->quantity,
                ]);

                // 🔹 Actualizar stock real
                $detail->product->increment('stock', $detail->quantity);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
