<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->label('Imagen')
                    ->default(asset('images/default-product.png'))
                    ->square(),

                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                
                TextColumn::make('presentations_count')
                    ->label('Presentaciones')
                    ->counts('presentations')
                    ->badge()
                    ->sortable(),

                TextColumn::make('brand.name') // usa relación 'brand'
                    ->label('Marca')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('categories') // muestra todas las categorías
                    ->label('Categorías')
                    ->formatStateUsing(fn($state, $record) => $record->categories->pluck('name')->join(', '))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Publicado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make()->label('Editar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
