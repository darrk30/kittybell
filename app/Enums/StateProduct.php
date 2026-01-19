<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StateProduct: string implements HasColor, HasLabel
{
    case Activo = 'activo';
    case Inactivo = 'inactivo';
    case Archivado = 'archivado';

    public function getLabel(): string
    {
        return match ($this) {
            self::Activo => 'Activo',
            self::Inactivo => 'Inactivo',
            self::Archivado => 'Archivado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Activo => 'success',
            self::Inactivo => 'warning',
            self::Archivado => 'danger',
        };
    }
}
