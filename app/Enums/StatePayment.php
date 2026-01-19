<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatePayment: string implements HasColor, HasIcon, HasLabel
{
    case Pendiente = 'Pendiente';
    case Pagado = 'Pagado';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente',
            self::Pagado => 'Pagado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pendiente => 'warning',
            self::Pagado => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pendiente => 'heroicon-o-clock',
            self::Pagado => 'heroicon-o-check-circle',
        };
    }

    public static function options(): array
    {
        return [
            self::Pendiente->value => 'Pendiente',
            self::Pagado->value => 'Pagado',
        ];
    }
    
}
