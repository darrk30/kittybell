<?php

namespace App\Filament\Resources\DeliveryTypes\Pages;

use App\Filament\Resources\DeliveryTypes\DeliveryTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeliveryType extends EditRecord
{
    protected static string $resource = DeliveryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
