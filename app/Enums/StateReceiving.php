<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StateReceiving: string implements HasColor, HasIcon, HasLabel
{
    case PorRecibir = 'Por recibir';
    case Recibido = 'Recibido';

    public function getLabel(): string
    {
        return match ($this) {
            self::PorRecibir => 'Por recibir',
            self::Recibido => 'Recibido',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::PorRecibir => 'warning',
            self::Recibido => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PorRecibir => 'heroicon-o-truck',
            self::Recibido => 'heroicon-o-check-circle',
        };
    }

    public static function options(): array
    {
        return [
            self::PorRecibir->value => 'Por Recibir',
            self::Recibido->value => 'Recibido',
        ];
    }
}
