<?php

namespace App\Filament\Resources\Sales\Tables;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 🧾 Tipo de comprobante
                TextColumn::make('document_type')
                    ->label('Comprobante')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                // 🧾 Serie + Correlativo
                TextColumn::make('series')
                    ->label('N° Documento')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->correlative
                            ? "{$record->series}-{$record->correlative}"
                            : $record->series
                    )
                    ->sortable()
                    ->searchable(),

                TextColumn::make('client.name')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('deliveryType.name')
                    ->label('Tipo de entrega')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('deliveryType.extra_price')
                    ->label('Costo entrega')
                    ->formatStateUsing(fn($state) => 'S/ ' . number_format($state ?? 0, 2))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => 'S/ ' . number_format($state ?? 0, 2))
                    ->sortable(),

                TextColumn::make('discount')
                    ->label('Descuento')
                    ->formatStateUsing(fn($state) => 'S/ ' . number_format($state ?? 0, 2))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->visible(fn($record) => $record->status->value !== 'Anulado'),
                Action::make('ticket')
                    ->label('Ticket')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('pdf-sale', ['sale' => $record])
                            // 👇 Esto fuerza el tamaño del papel tipo ticket
                            ->setPaper([0, 0, 226.77, 600], 'portrait');
                        // 226.77 puntos ≈ 80mm de ancho
                        // 600 puntos de alto (ajústalo si necesitas más largo)

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, "ticket_{$record->series}-{$record->correlative}.pdf");
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
