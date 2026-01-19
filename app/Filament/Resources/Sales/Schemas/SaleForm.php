<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Enums\DocumentType;
use App\Enums\StateProduct;
use App\Enums\StateSale;
use App\Models\CashSummary;
use App\Models\DeliveryType;
use App\Models\DocumentType as ModelsDocumentType;
use App\Models\Presentation;
use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Collection;

class SaleForm
{
    private static function recalculateLineAndTotal(Get $get, Set $set): void
    {
        $q = (float) ($get('quantity') ?? 0);
        $p = (float) ($get('price') ?? 0);
        $d = (float) ($get('discount') ?? 0);
        $subtotal = max(0, $q * $p - $d);
        $set('subtotal', round($subtotal, 2));

        // Recalcular el total general
        $details = collect($get('../../details') ?? []);
        $sum = $details->sum(fn($item) => (float) ($item['subtotal'] ?? 0));
        $saleDiscount = (float) ($get('../../discount') ?? 0);

        // Obtener el precio extra del tipo de entrega
        $deliveryTypeId = $get('../../delivery_type_id');
        $deliveryExtraPrice = 0;
        if ($deliveryTypeId) {
            $deliveryType = DeliveryType::find($deliveryTypeId);
            $deliveryExtraPrice = $deliveryType ? (float) $deliveryType->extra_price : 0;
        }

        $total = max(0, $sum - $saleDiscount + $deliveryExtraPrice);
        $set('../../total_amount', round($total, 2));
    }

    private static function recalculateTotal(Get $get, Set $set): void
    {
        $details = collect($get('details') ?? []);
        $sum = $details->sum(fn($item) => (float) ($item['subtotal'] ?? 0));
        $saleDiscount = (float) ($get('discount') ?? 0);

        // Obtener el precio extra del tipo de entrega
        $deliveryTypeId = $get('delivery_type_id');
        $deliveryExtraPrice = 0;
        if ($deliveryTypeId) {
            $deliveryType = DeliveryType::find($deliveryTypeId);
            $deliveryExtraPrice = $deliveryType ? (float) $deliveryType->extra_price : 0;
        }

        $total = max(0, $sum - $saleDiscount + $deliveryExtraPrice);
        $set('total_amount', round($total, 2));
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Información de la venta')
                ->columnSpan('full')
                ->schema([
                    // Fila 1: Tipo de documento | Serie | Caja
                    Grid::make(3)
                        ->schema([
                            Select::make('document_type')
                                ->label('Tipo de Documento')
                                ->options(DocumentType::options())
                                ->default(DocumentType::NotaVenta->value)
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    if ($state) {
                                        $series = DocumentType::from($state)->getSeriesCode();
                                        $set('series', $series);
                                    }
                                }),

                            TextInput::make('series')
                                ->label('Serie')
                                ->disabled()
                                ->dehydrated(true)
                                ->default(DocumentType::NotaVenta->getSeriesCode())
                                ->required(),

                            Select::make('cash_summary_id')
                                ->label('Caja')
                                ->relationship('cashSummary', 'name')
                                ->default(function () {
                                    $onlyCash = CashSummary::first();
                                    return CashSummary::count() === 1 ? $onlyCash->id : null;
                                })
                                ->searchable()
                                ->required(),
                        ]),

                    // Fila 2: Cliente | Tipo de entrega | Estado
                    Grid::make(3)
                        ->schema([
                            Select::make('client_id')
                                ->label('Cliente')
                                ->relationship('client', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->createOptionForm([
                                    TextInput::make('name')->label('Nombre')->required(),
                                    Select::make('document_type_id')
                                        ->label('Tipo de documento')
                                        ->relationship('documentType', 'name'),
                                    TextInput::make('document_number')->label('Nro. Documento'),
                                    TextInput::make('email')->email(),
                                    TextInput::make('phone')->label('Teléfono'),
                                    TextInput::make('address')->label('Dirección'),
                                    Toggle::make('is_active')->label('Activo')->default(true),
                                ]),

                            Select::make('delivery_type_id')
                                ->label('Tipo de entrega')
                                ->options(fn() => DeliveryType::where('is_active', 1)
                                    ->get()
                                    ->mapWithKeys(fn($d) => [
                                        $d->id => "{$d->name} (S/{$d->extra_price})"
                                    ])
                                    ->toArray())
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    if ($state) {
                                        $deliveryType = DeliveryType::find($state);
                                        $extraPrice = $deliveryType ? (float) $deliveryType->extra_price : 0;
                                        $set('delivery_extra_price', $extraPrice);
                                    } else {
                                        $set('delivery_extra_price', 0);
                                    }
                                    self::recalculateTotal($get, $set);
                                }),

                            ToggleButtons::make('status')
                                ->label('Estado de la Venta')
                                ->inline()
                                ->options(StateSale::class)
                                ->required()
                                ->default(StateSale::Aceptado),
                        ]),

                    // Fila 3: Información adicional
                    RichEditor::make('notes')
                        ->label('Información adicional')
                        ->columnSpanFull(),
                ]),

            Repeater::make('details')
                ->label('Pruducto de la venta')
                ->columnSpan('full')
                ->table([
                    TableColumn::make('Producto')->width('300px'),
                    TableColumn::make('Tono')->width('300px'),
                    TableColumn::make('Cantidad'),
                    TableColumn::make('P. Unitario'),
                    TableColumn::make('Desc. línea'),
                    TableColumn::make('Subtotal'),
                ])
                ->relationship('details')
                ->schema([

                    Select::make('product_id')
                        ->label('Producto')
                        ->options(fn() => Product::where('is_active', StateProduct::Activo->value)
                            ->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive() // 👈 Importante para que actualice el siguiente campo
                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                            $price = Product::find($state)?->price ?? 0;
                            $set('price', (float) $price);
                            self::recalculateLineAndTotal($get, $set);
                            $set('presentation_id', null);
                        }),

                    Select::make('presentation_id')
                        ->label('Presentación')
                        ->options(function (Get $get) {
                            $productId = $get('product_id');
                            if (!$productId) {
                                return []; // Si no hay producto, no mostrar nada
                            }
                            return Presentation::where('product_id', $productId)->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->reactive(),
                        
                    TextInput::make('quantity')
                        ->label('Cantidad')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateLineAndTotal($get, $set)),
                    TextInput::make('price')
                        ->label('Precio unitario')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->required()
                        ->live(onBlur: true)
                        ->suffix('S/')
                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateLineAndTotal($get, $set)),
                    TextInput::make('discount')
                        ->label('Descuento')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->live(onBlur: true)
                        ->suffix('S/')
                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateLineAndTotal($get, $set)),
                    TextInput::make('subtotal')
                        ->label('Subtotal')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(true)
                        ->suffix('S/'),
                ])
                ->addActionLabel('Agregar producto')
                ->live()
                ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateTotal($get, $set))
                ->deleteAction(fn($action) => $action->after(fn(Get $get, Set $set) => self::recalculateTotal($get, $set))),


            Section::make('Resumen de la venta')
                ->columnSpan('full')
                ->columns(3)
                ->schema([
                    TextInput::make('discount')
                        ->label('Descuento total')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateTotal($get, $set))
                        ->suffix('S/'),

                    TextInput::make('delivery_extra_price')
                        ->label('Precio extra de entrega')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(false)
                        ->suffix('S/')
                        ->afterStateHydrated(function (TextInput $component, Get $get, Set $set) {
                            $deliveryTypeId = $get('delivery_type_id');
                            if ($deliveryTypeId) {
                                $deliveryType = DeliveryType::find($deliveryTypeId);
                                $component->state($deliveryType ? $deliveryType->extra_price : 0);
                            }
                        }),

                    TextInput::make('total_amount')
                        ->label('Monto total')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(true)
                        ->suffix('S/'),
                ]),
        ]);
    }
}
