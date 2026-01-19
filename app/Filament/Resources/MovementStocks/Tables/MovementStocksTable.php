<?php

namespace App\Filament\Resources\MovementStocks\Tables;

use App\Enums\MovementStockType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class MovementStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('document')
                    ->label('Documento')
                    ->getStateUsing(function ($record) {
                        $type = $record->movement_type instanceof MovementStockType
                            ? $record->movement_type->value
                            : $record->movement_type;

                        return match ($type) {
                            'venta' => $record->sale
                                ? ($record->sale->document_type?->getLabel() ?? '') . ' ' . $record->sale->series . '-' . str_pad($record->sale->correlative, 6, '0', STR_PAD_LEFT)
                                : '-',
                            'compra' => $record->purchase
                                ? ($record->purchase->document_type?->getLabel() ?? '') . ' ' . $record->purchase->series . '-' . str_pad($record->purchase->correlative, 6, '0', STR_PAD_LEFT)
                                : '-',
                            'ajuste_entrada', 'ajuste_salida' => 'Ajuste de Stock',
                            'stock_inicial' => 'Stock Inicial',
                            default => 'Sin Documento',
                        };
                    }),

                TextColumn::make('product.name')
                    ->label('Producto'),

                TextColumn::make('movement_type')
                    ->label('Tipo de Movimiento')
                    ->badge()
                    ->color(fn($state): string => match ($state instanceof MovementStockType ? $state->value : $state) {
                        'compra', 'ajuste_entrada' => 'success',
                        'venta', 'ajuste_salida' => 'danger',
                        'StockInicial', 'stock_inicial' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(function ($state) {
                        $value = $state instanceof MovementStockType ? $state->value : $state;
                        return match ($value) {
                            'compra' => 'Compra',
                            'venta' => 'Venta',
                            'ajuste_entrada' => 'Ajuste Entrada',
                            'ajuste_salida' => 'Ajuste Salida',
                            'stock_inicial' => 'Stock Inicial',
                            default => ucfirst((string) $value),
                        };
                    }),

                TextColumn::make('entrada')
                    ->label('Entrada')
                    ->getStateUsing(
                        fn($record) =>
                        in_array($record->movement_type instanceof MovementStockType ? $record->movement_type->value : $record->movement_type, ['compra', 'ajuste_entrada', 'stock_inicial'])
                            ? number_format($record->quantity, 2)
                            : null
                    ),

                TextColumn::make('salida')
                    ->label('Salida')
                    ->getStateUsing(
                        fn($record) =>
                        in_array($record->movement_type instanceof MovementStockType ? $record->movement_type->value : $record->movement_type, ['venta', 'ajuste_salida'])
                            ? number_format($record->quantity, 2)
                            : null
                    ),

                TextColumn::make('balance')
                    ->label('Saldo Final')
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state ?? 0, 2)),
            ])
            ->filters([
                // Filtro tipo select para presentaciones
                SelectFilter::make('product_id')
                    ->label('Producto')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make('created_at')
                    ->label('Order date')
                    ->schema([
                        DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
