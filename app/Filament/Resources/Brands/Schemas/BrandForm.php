<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
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

                        Toggle::make('is_active')
                            ->label('Activo')
                            ->required()
                            ->default(true)
                            ->inline(false),
                    ])->columnSpanFull(),
            ]);
    }
}
