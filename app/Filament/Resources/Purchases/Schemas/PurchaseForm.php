<?php

namespace App\Filament\Resources\Purchases\Schemas;

use App\Enums\DocumentType;
use App\Enums\StatePayment;
use App\Enums\StateProduct;
use App\Enums\StatePurchase;
use App\Enums\StateReceiving;
use App\Models\CashSummary;
use App\Models\Presentation;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class PurchaseForm
{
    /**
     * Recalcula el subtotal de una línea del repeater y luego el total general
     * Se usa cuando se modifica un campo dentro de una línea (presentation, quantity, price, discount de línea)
     */
    private static function recalculateLineAndTotal(Get $get, Set $set): void
    {
        // 1. Actualizar subtotal de la línea actual
        $q = (float) ($get('quantity') ?? 0);
        $p = (float) ($get('cost') ?? 0);
        $d = (float) ($get('discount') ?? 0);
        $subtotal = max(0, $q * $p - $d);
        $set('subtotal', round($subtotal, 2));

        // 2. Actualizar total general (subimos 2 niveles: línea -> details -> formulario)
        $details = collect($get('../../details') ?? []);
        $sum = $details->sum(fn($item) => (float) ($item['subtotal'] ?? 0));
        $purchaseDiscount = (float) ($get('../../discount') ?? 0);
        $total = max(0, $sum - $purchaseDiscount);
        $set('../../total_amount', round($total, 2));
    }

    /**
     * Recalcula solo el total general (sin modificar subtotales de líneas)
     * Se usa cuando se agrega/elimina una línea o se cambia el descuento total
     */
    private static function recalculateTotal(Get $get, Set $set): void
    {
        // Ya estamos en el nivel del formulario
        $details = collect($get('details') ?? []);
        $sum = $details->sum(fn($item) => (float) ($item['subtotal'] ?? 0));
        $purchaseDiscount = (float) ($get('discount') ?? 0);
        $total = max(0, $sum - $purchaseDiscount);
        $set('total_amount', round($total, 2));
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Información de la compra')
                ->columnSpan('full')
                ->schema([
                    // 🔹 Primera fila: tipo de documento, serie y correlativo
                    Section::make('')
                        ->columns(3)
                        ->schema([
                            Select::make('document_type')
                                ->label('Tipo de documento')
                                ->options(
                                    collect(DocumentType::options())
                                        ->reject(fn($label, $key) => $key === 'NotaVenta' || $label === 'Nota de Venta')
                                        ->toArray()
                                )
                                ->required()
                                ->live(),

                            TextInput::make('series')
                                ->label('Serie')
                                ->required(),

                            TextInput::make('correlative')
                                ->label('Correlativo')
                                ->required(),
                        ]),

                    // 🔹 Segunda fila: proveedor y caja
                    Section::make('')
                        ->columns(3)
                        ->schema([
                            Select::make('supplier_id')
                                ->label('Proveedor')
                                ->relationship('supplier', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('cash_summary_id')
                                ->label('Caja')
                                ->preload()
                                ->relationship('cashSummary', 'name')
                                ->default(function () {
                                    $onlyCash = CashSummary::first();
                                    return CashSummary::count() === 1 ? $onlyCash->id : null;
                                })
                                ->visible(fn(Get $get) => $get('document_type') !== 'Cotización')
                                ->dehydrated(fn(Get $get) => $get('document_type') !== 'Cotización')
                                ->searchable()
                                ->required(),

                            DatePicker::make('purchase_date')
                                ->label('Fecha de compra')
                                ->default(now())
                                ->required(),
                        ]),

                    // 🔹 Tercera fila: fecha y observaciones
                    Section::make('')
                        ->columns(1)
                        ->schema([

                            RichEditor::make('notes')
                                ->label('Información adicional')
                                ->columnSpan('full'),
                        ]),

                    // 🔹 Estados (pago / recepción)
                    Section::make('')
                        ->columns(3)
                        ->schema([
                            ToggleButtons::make('receiving_status')
                                ->label('Estado de recepción')
                                ->inline()
                                ->options(StateReceiving::class)
                                ->default(StateReceiving::PorRecibir->value)
                                ->required()
                                ->visible(fn(Get $get) => $get('document_type') !== 'Cotización')
                                ->dehydrated(fn(Get $get) => $get('document_type') !== 'Cotización')
                                ->disabled(fn(Get $get) => $get('status') === StatePurchase::Anulado->value),

                            ToggleButtons::make('payment_status')
                                ->label('Estado de pago')
                                ->inline()
                                ->options(StatePayment::class)
                                ->default(StatePayment::Pagado->value)
                                ->required()
                                ->visible(fn(Get $get) => $get('document_type') !== 'Cotización')
                                ->dehydrated(fn(Get $get) => $get('document_type') !== 'Cotización')
                                ->disabled(fn(Get $get) => $get('status') === StatePurchase::Anulado->value),

                            ToggleButtons::make('status')
                                ->label('Estado de la Venta')
                                ->inline()
                                ->options(StatePurchase::class)
                                ->required()
                                ->default(StatePurchase::Aceptado),
                        ]),
                ]),

            Section::make('Items')
                ->description('Agrega los productos a comprar (Presentación).')
                ->columnSpan('full')
                ->schema([
                    Repeater::make('details')
                        ->table([
                            TableColumn::make('Name')->width('300px'),
                            TableColumn::make('Tono')->width('300px'),
                            TableColumn::make('Cantidad'),
                            TableColumn::make('P. Unitario'),
                            TableColumn::make('Descueto'),
                            TableColumn::make('Sub Total'),
                        ])
                        ->relationship('details')
                        ->label('')
                        ->schema([
                            // Select::make('product_id')
                            //     ->label('Producto')
                            //     ->options(fn() => Product::where('is_active', '!=', 'archivado')
                            //         ->get()
                            //         ->mapWithKeys(fn($p) => [
                            //             $p->id => "{$p->name}"
                            //         ])
                            //         ->toArray())
                            //     ->searchable()
                            //     ->preload()
                            //     ->required()
                            //     ->live(onBlur: true)
                            //     ->afterStateUpdated(function ($state, Get $get, Set $set) {
                            //         $cost = Product::find($state)?->cost ?? 0;
                            //         $set('cost', (float) $cost);
                            //         self::recalculateLineAndTotal($get, $set);
                            //     }),
                            Select::make('product_id')
                                ->label('Producto')
                                ->options(fn() => Product::where('is_active', StateProduct::Activo->value)
                                    ->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->reactive() // 👈 Importante para que actualice el siguiente campo
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    $cost = Product::find($state)?->cost ?? 0;
                                    $set('cost', (float) $cost);
                                    self::recalculateLineAndTotal($get, $set);
                                    // Al cambiar el producto, limpiamos la presentación
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

                            TextInput::make('cost')
                                ->label('P. compra')
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->minValue(0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateLineAndTotal($get, $set)),

                            TextInput::make('discount')
                                ->label('Desc. línea')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateLineAndTotal($get, $set)),

                            TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->default(0)
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true),
                        ])
                        ->addActionLabel('Agregar presentación')
                        ->live()
                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateTotal($get, $set))
                        ->deleteAction(
                            fn($action) => $action->after(fn(Get $get, Set $set) => self::recalculateTotal($get, $set))
                        ),
                ]),

            Section::make('Resumen de la compra')
                ->columnSpan('full')
                ->schema([
                    TextInput::make('discount')
                        ->label('Descuento total')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateTotal($get, $set)),

                    TextInput::make('total_amount')
                        ->label('Monto total')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(true)
                        ->suffix('S/')
                        ->columnSpan('full'),
                ]),
        ]);
    }
}
