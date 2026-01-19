<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StateSale: string implements HasColor, HasLabel
{
    case Aceptado = 'Aceptado';
    case Anulado = 'Anulado';

    public function getLabel(): string
    {
        return match ($this) {
            self::Aceptado => 'Aceptado',
            self::Anulado => 'Anulado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Aceptado => 'success',
            self::Anulado => 'danger',
        };
    }

    
}
