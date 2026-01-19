<?php

namespace App\Filament\Resources\MovementStocks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MovementStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('presentation_id')
                    ->required()
                    ->numeric(),
                TextInput::make('movement_type')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('balance')
                    ->numeric(),
                TextInput::make('purchase_id')
                    ->numeric(),
                TextInput::make('sale_id')
                    ->numeric(),
                TextInput::make('adjustment_stock_id')
                    ->numeric(),
            ]);
    }
}
