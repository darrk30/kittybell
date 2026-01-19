<?php

namespace App\Filament\Resources\Purchases\Tables;

use App\Enums\StatePurchase;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 🧾 Tipo de comprobante
                TextColumn::make('document_type')
                    ->label('Comprobante')
                    ->badge()
                    ->sortable(),

                // 🧾 Serie + Correlativo
                TextColumn::make('correlative')
                    ->label('N° Documento')
                    ->formatStateUsing(fn($state, $record) => "{$record->series}-{$record->correlative}")
                    ->sortable()
                    ->searchable(),

                // 👤 Proveedor
                TextColumn::make('supplier.name')
                    ->label('Proveedor')
                    ->searchable()
                    ->sortable(),

                // 🟢 Estado de compra
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),

                // 🟡 Estado de pago
                TextColumn::make('payment_status')
                    ->badge()
                    ->label('Pago')
                    ->sortable(),

                // 🔵 Estado de recepción
                TextColumn::make('receiving_status')
                    ->badge()
                    ->label('Recepción')
                    ->sortable(),

                // 💸 Descuento
                TextColumn::make('discount')
                    ->label('Descuento')
                    ->formatStateUsing(fn($state) => 'S/ ' . number_format($state ?? 0, 2))
                    ->sortable(),

                // 💰 Total
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => 'S/ ' . number_format($state ?? 0, 2))
                    ->sortable(),

                // 📅 Fecha
                TextColumn::make('purchase_date')
                    ->label('Fecha')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn($record) => $record->status->value !== 'Anulado'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                // 🗓️ Filtro de rango de fechas
                Filter::make('purchase_date')
                    ->label('Rango de fechas')
                    ->schema([
                        DatePicker::make('start_date')->label('Desde'),
                        DatePicker::make('end_date')->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start_date'], fn($q) => $q->whereDate('purchase_date', '>=', $data['start_date']))
                            ->when($data['end_date'], fn($q) => $q->whereDate('purchase_date', '<=', $data['end_date']));
                    }),

                // 🏷️ Filtro por estado de compra
                SelectFilter::make('status')
                    ->label('Estado de compra')
                    ->options([
                        StatePurchase::Aceptado->value => 'Aceptado',
                        StatePurchase::Anulado->value => 'Anulado',
                    ])
                    ->default(StatePurchase::Aceptado->value)
            ])
            ->searchable([
                'series',
                'correlative',
                'supplier.name',
            ])
            ->defaultSort('id', 'desc');
    }
}
