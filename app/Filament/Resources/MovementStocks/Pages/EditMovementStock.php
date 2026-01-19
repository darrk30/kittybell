<?php

namespace App\Filament\Resources\MovementStocks\Pages;

use App\Filament\Resources\MovementStocks\MovementStockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMovementStock extends EditRecord
{
    protected static string $resource = MovementStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
