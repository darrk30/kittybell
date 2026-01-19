<?php

namespace App\Filament\Resources\MovementStocks\Pages;

use App\Filament\Resources\MovementStocks\MovementStockResource;
use App\Filament\Resources\MovementStocks\Widgets\MovementStockStatsOverview;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListMovementStocks extends ListRecords
{
    use ExposesTableToWidgets;
    
    protected static string $resource = MovementStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {    
        return MovementStockResource::getWidgets();
    }
}