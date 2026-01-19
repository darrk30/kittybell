<?php

namespace App\Filament\Resources\AdjustmentStocks\Tables;

use App\Enums\MovementStockType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class AdjustmentStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Tipo de ajuste
                TextColumn ::make('movement_type')
                    ->label('Tipo de ajuste')
                    ->badge()
                    ->sortable(),

                // Motivo
                TextColumn::make('motive')
                    ->label('Motivo')
                    ->searchable()
                    ->sortable(),

                // Fecha del ajuste
                TextColumn::make('date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                // Fecha de creación (opcional)
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Fecha de actualización (opcional)
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc'); // Ordenar por fecha de ajuste descendente
    }
}
