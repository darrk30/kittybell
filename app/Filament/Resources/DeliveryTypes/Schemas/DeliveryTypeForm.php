<?php

namespace App\Filament\Resources\DeliveryTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeliveryTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required(),

                        TextInput::make('extra_price')
                            ->label('Precio Extra')
                            ->required()
                            ->numeric()
                            ->default(0.0),

                        Toggle::make('is_active')
                            ->label('Activo')
                            ->required()
                            ->default(true)
                            ->inline(false),
                    ])->columnSpanFull(),
            ]);
    }
}
