<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Cliente')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // Columna 1
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required(),

                                Select::make('document_type_id')
                                    ->label('Tipo de Documento')
                                    ->relationship('documentType', 'name')
                                    ->required(),

                                TextInput::make('document_number')
                                    ->label('Número de Documento')
                                    ->required(),

                                // Columna 2
                                TextInput::make('phone')
                                    ->label('Teléfono')
                                    ->tel(),

                                TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email(),

                                TextInput::make('address')
                                    ->label('Dirección'),
                            ]),

                        // Estado al final
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->required()
                            ->inline(false),
                    ])->columnSpanFull(),
            ]);
    }
}
