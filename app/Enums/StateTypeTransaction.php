<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StateTypeTransaction: string implements HasColor, HasLabel
{
    case Ingreso = 'Ingreso';
    case Egreso = 'Egreso';
    case Anulado = 'Anulado';

    public function getLabel(): string
    {
        return match ($this) {
            self::Ingreso => 'Ingreso',
            self::Egreso => 'Egreso',
            self::Anulado => 'Anulado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Ingreso => 'success',
            self::Egreso => 'danger',
            self::Anulado => 'danger',
        };
    }

    
}
