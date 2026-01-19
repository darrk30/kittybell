<?php

namespace App\Filament\Resources\CashSummaries\RelationManagers;

use App\Filament\Resources\Sales\SaleResource;
use App\Models\Sale;
use Filament\Actions\Action as ActionsAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions'; // 🔹 en minúscula (nombre del método en el modelo padre)

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('amount')
                    ->label('Monto')
                    ->money('PEN', true)
                    ->sortable(),

                TextColumn::make('transaction_type')
                    ->label('Tipo')
                    ->badge()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->headerActions([]) // ❌ Sin botones en el header
            ->recordActions([
                ActionsAction::make('openTransactionable')
                    ->tooltip('Ver registro relacionado')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('info')
                    ->url(function ($record): ?string {
                        // Si no hay registro relacionado, retorna null
                        if (! $record->transactionable) {
                            return null;
                        }

                        // Determina el recurso de Filament según el tipo de modelo
                        $resourceClass = match (get_class($record->transactionable)) {
                            \App\Models\Sale::class => \App\Filament\Resources\Sales\SaleResource::class,
                            \App\Models\Purchase::class => \App\Filament\Resources\Purchases\PurchaseResource::class,
                            \App\Models\Spent::class => \App\Filament\Resources\Spents\SpentResource::class,
                            default => null,
                        };

                        if (! $resourceClass) {
                            return null;
                        }

                        // Genera la URL de edición del registro en Filament
                        return $resourceClass::getUrl('view', ['record' => $record->transactionable->id]);
                        // return $resourceClass::getUrl('view', ['record' => $record->transactionable->id]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn($record) => ! $record->transactionable),
            ])

            ->toolbarActions([]); // ❌ Sin acciones masivas
    }
}
