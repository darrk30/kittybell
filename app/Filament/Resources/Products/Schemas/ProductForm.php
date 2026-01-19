<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\StateProduct;
use App\Models\Brand;
use App\Models\Category;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Support\Facades\Storage;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre del producto')
                                    ->required(),

                                TextInput::make('price')
                                    ->label('Precio')
                                    ->numeric()
                                    ->prefix('S/.')
                                    ->required(),

                                TextInput::make('cost')
                                    ->label('Costo')
                                    ->numeric()
                                    ->prefix('S/.')
                                    ->required(),

                                TextInput::make('stock')
                                    ->label('Stock')
                                    ->numeric()
                                    ->required()
                                    ->disabled(fn($record) => $record && $record->exists),

                                Textarea::make('description')
                                    ->label('Descripción'),

                                FileUpload::make('image')
                                    ->label('Imagen')
                                    ->image()
                                    ->disk('public')
                                    ->directory('products')
                                    ->visibility('public')
                                    ->preserveFilenames(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Status')
                            ->schema([
                                Select::make('is_active')
                                    ->label('Estado')
                                    ->options([
                                        StateProduct::Activo->value => StateProduct::Activo->getLabel(),
                                        StateProduct::Inactivo->value => StateProduct::Inactivo->getLabel(),
                                    ])
                                    ->default(StateProduct::Activo->value)
                                    ->required(),

                                DateTimePicker::make('created_at')
                                    ->label('Fecha de creación')
                                    ->default(now())
                                    ->disabled(),
                            ]),

                        Section::make('Associations')->label('Asociaciones')
                            ->schema([
                                // 🔹 Marca
                                Select::make('brand_id')
                                    ->label('Marca')
                                    ->relationship('brand', 'name', fn($query) => $query->where('is_active', true)) // Solo activas
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nombre de la marca')
                                            ->required(),
                                    ])
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalHeading('Crear nueva marca')
                                            ->modalSubmitActionLabel('Guardar marca')
                                            ->mutateDataUsing(fn(array $data) => [
                                                ...$data,
                                                'is_active' => true, // Por defecto activa
                                            ]);
                                    }),

                                // 🔹 Categorías (múltiples)
                                Select::make('categories')
                                    ->label('Categorías')
                                    ->multiple()
                                    ->relationship('categories', 'name', fn($query) => $query->where('is_active', true)) // Solo activas
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nombre de la categoría')
                                            ->required(),
                                    ])
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalHeading('Crear nueva categoría')
                                            ->modalButton('Guardar categoría')
                                            ->mutateFormDataUsing(fn(array $data) => [
                                                ...$data,
                                                'is_active' => true, // Por defecto activa
                                            ]);
                                    }),
                            ])

                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
