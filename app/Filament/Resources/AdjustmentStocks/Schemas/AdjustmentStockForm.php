<?php

namespace App\Filament\Resources\AdjustmentStocks\Schemas;

use App\Enums\MovementStockType;
use App\Enums\StateProduct;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use App\Models\Presentation;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Grid;

class AdjustmentStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Select::make('movement_type')
                            ->label('Tipo de movimiento')
                            ->options([
                                MovementStockType::AjusteEntrada->value => MovementStockType::AjusteEntrada->getLabel(),
                                MovementStockType::AjusteSalida->value => MovementStockType::AjusteSalida->getLabel(),
                            ])
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('motive')
                            ->label('Motivo')
                            ->required()
                            ->columnSpan(1),
                            
                        DatePicker::make('date')
                            ->label('Fecha')
                            ->required()
                            ->default(Carbon::today())
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),

                RichEditor::make('notes')
                    ->label('Descripcion')
                    ->columnSpanFull(),

                // 🧾 Tabla de productos
                Repeater::make('details')
                    ->label('Productos')
                    ->relationship('details') // <- relación en el modelo AdjustmentStock
                    ->schema([
                        Select::make('product_id')
                            ->label('Producto')
                            ->searchable()
                            ->preload()
                            ->options(fn() => Product::where('is_active', StateProduct::Activo->value)
                                ->get()
                                ->mapWithKeys(fn($p) => [
                                    $p->id => "{$p->name}"
                                ])
                                ->toArray())
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Al seleccionar producto, autocompleta el precio
                                $product = Product::find($state);
                                if ($product) {
                                    $set('cost', $product->price);
                                }
                            })
                            ->required(),

                        TextInput::make('quantity')
                            ->label('Cantidad')
                            ->default(0)
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        TextInput::make('cost')
                            ->label('P. Compra')
                            ->default(0)
                            ->minValue(0)
                            ->numeric()
                            ->prefix('S/.')
                            ->required(),
                    ])
                    ->columns(3)
                    ->addActionLabel('Agregar producto')
                    ->columnSpanFull(),
            ]);
    }
}
