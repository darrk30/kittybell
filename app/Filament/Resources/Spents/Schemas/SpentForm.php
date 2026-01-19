<?php

namespace App\Filament\Resources\Spents\Schemas;

use App\Models\CashSummary;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SpentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Movimiento')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required(),

                        Textarea::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),

                        TextInput::make('amount')
                            ->label('Monto')
                            ->required()
                            ->numeric(),

                        Select::make('cash_summary_id')
                            ->label('Caja')
                            ->relationship('cashSummary', 'name')
                            ->default(function () {
                                $onlyCash = CashSummary::first();
                                return CashSummary::count() === 1 ? $onlyCash->id : null;
                            })
                            ->searchable()
                            ->required(),

                        DatePicker::make('date')
                            ->label('Fecha')
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->required()
                            ->inline(false),
                    ])->columnSpanFull(),
            ]);
    }
}
