<?php

namespace App\Filament\Resources\AdjustmentStocks\Pages;

use App\Filament\Resources\AdjustmentStocks\AdjustmentStockResource;
use App\Models\MovementStock;
use Filament\Resources\Pages\CreateRecord;

class CreateAdjustmentStock extends CreateRecord
{
    protected static string $resource = AdjustmentStockResource::class;

    protected function afterCreate(): void
    {
        $adjustment = $this->record; // el ajuste recién creado

        foreach ($adjustment->details as $detail) {
            // Evitar duplicar movimientos
            $exists = MovementStock::where('adjustment_stock_id', $adjustment->id)->where('product_id', $detail->product_id)->exists();

            if (! $exists) {
                $product = $detail->product;

                // Determinar tipo de movimiento (entrada o salida)
                $movementType = $adjustment->movement_type;

                // Calcular nuevo stock según tipo
                if ($movementType->value === 'ajuste_entrada') {
                    $product->increment('stock', $detail->quantity);
                } elseif ($movementType->value === 'ajuste_salida') {
                    $product->decrement('stock', $detail->quantity);
                }

                // Crear movimiento de stock
                MovementStock::create([
                    'product_id'            => $product->id,
                    'quantity'            => $detail->quantity,
                    'movement_type'       => $movementType,
                    'sale_id'             => null,
                    'purchase_id'         => null,
                    'adjustment_stock_id' => $adjustment->id,
                    'balance'             => $product->stock,
                ]);
            }
        }
    }
}
