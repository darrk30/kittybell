<?php

namespace App\Filament\Resources\AdjustmentStocks\Pages;

use App\Filament\Resources\AdjustmentStocks\AdjustmentStockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdjustmentStocks extends ListRecords
{
    protected static string $resource = AdjustmentStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
