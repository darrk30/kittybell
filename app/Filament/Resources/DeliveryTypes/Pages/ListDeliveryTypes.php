<?php

namespace App\Filament\Resources\DeliveryTypes\Pages;

use App\Filament\Resources\DeliveryTypes\DeliveryTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryTypes extends ListRecords
{
    protected static string $resource = DeliveryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
