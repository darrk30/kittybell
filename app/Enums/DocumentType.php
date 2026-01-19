<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DocumentType: string implements HasLabel
{
    case Factura = 'Factura';
    case Boleta = 'Boleta';
    case Cotizacion = 'Cotización';
    case NotaVenta = 'Nota de Venta';

    public function getLabel(): string
    {
        return match ($this) {
            self::Factura => 'Factura',
            self::Boleta => 'Boleta',
            self::Cotizacion => 'Cotización',
            self::NotaVenta => 'Nota de Venta',
        };
    }

    /** 🔹 Helper para Selects en Filament */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
            ->toArray();
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Factura => 'success',     // Verde
            self::Boleta => 'success',         // Azul
            self::Cotizacion => 'warning',  // Amarillo
            self::NotaVenta => 'success',    // Rojo
        };
    }

    /** 🔹 Devuelve el código de serie según el tipo */
    public function getSeriesCode(): string
    {
        return match ($this) {
            self::Factura => 'F001',
            self::Boleta => 'B001',
            self::NotaVenta => 'NV01',
            self::Cotizacion => 'COT1',
        };
    }
}
