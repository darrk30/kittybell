<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\DocumentType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del proveedor')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required(),

                        Select::make('document_type_id')
                            ->label('Tipo de documento')
                            ->relationship('documentType', 'code')
                            ->preload()
                            ->searchable()
                            ->required(),
                            
                        TextInput::make('document_number')
                            ->label('Número de documento'),

                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email(),

                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel(),

                        TextInput::make('address')
                            ->label('Dirección'),

                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2)          // mantiene los campos en 2 columnas
                    ->columnSpan('full'),  // la sección ocupa todo el ancho
            ]);
    }
}
