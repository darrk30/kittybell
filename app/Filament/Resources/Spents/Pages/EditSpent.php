<?php

namespace App\Filament\Resources\Spents\Pages;

use App\Filament\Resources\Spents\SpentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpent extends EditRecord
{
    protected static string $resource = SpentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
