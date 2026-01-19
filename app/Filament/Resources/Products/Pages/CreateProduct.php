<?php

namespace App\Filament\Resources\Products\Pages;

use App\Enums\MovementStockType;
use App\Filament\Resources\Products\ProductResource;
use App\Models\MovementStock;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected function afterCreate(): void
    {
        $product = $this->record; // Este es el producto que se acaba de crear

        // Verificamos si no existe un movimiento de stock para el producto
        if (!MovementStock::where('product_id', $product->id) // Suponiendo que `product_id` es el campo que une el movimiento con el producto
            ->where('movement_type', MovementStockType::StockInicial->value ?? 'stock_inicial')
            ->exists()) {

            // Crear un nuevo movimiento de stock para el producto
            MovementStock::create([
                'product_id'     => $product->id,           // Asociar al producto
                'quantity'       => $product->stock,        // El stock inicial del producto
                'movement_type'  => MovementStockType::StockInicial->value ?? 'stock_inicial',
                'balance'        => $product->stock,        // El balance inicial
            ]);
        }
    }
}
