<?php

namespace App\Filament\Resources\Spents\Pages;

use App\Filament\Resources\Spents\SpentResource;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListSpents extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = SpentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {    
        return SpentResource::getWidgets();
    }
}
