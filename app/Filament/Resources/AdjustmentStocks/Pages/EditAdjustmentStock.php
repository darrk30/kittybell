<?php

namespace App\Filament\Resources\AdjustmentStocks\Pages;

use App\Filament\Resources\AdjustmentStocks\AdjustmentStockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdjustmentStock extends EditRecord
{
    protected static string $resource = AdjustmentStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
