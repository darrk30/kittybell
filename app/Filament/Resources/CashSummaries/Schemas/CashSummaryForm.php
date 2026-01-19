<?php

namespace App\Filament\Resources\CashSummaries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CashSummaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('current_balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
