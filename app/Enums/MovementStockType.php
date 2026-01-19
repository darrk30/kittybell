<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MovementStockType: string implements HasLabel
{
    case Compra = 'compra';            // Compra u entrada de stock
    case Venta = 'venta';              // Venta u salida de stock
    case AjusteEntrada = 'ajuste_entrada'; // Ajuste de stock positivo
    case AjusteSalida = 'ajuste_salida';   // Ajuste de stock negativo
    case StockInicial = 'stock_inicial';   // Ajuste de stock negativo

    /**
     * Retorna la etiqueta legible para mostrar en Filament.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Compra => 'Compra',
            self::Venta => 'Venta',
            self::AjusteEntrada => 'Entrada',
            self::AjusteSalida => 'Salida',
            self::StockInicial => 'Stock Incial',
        };
    }

    /**
     * Helper para Selects en Filament
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
            ->toArray();
    }
}
