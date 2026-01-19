<?php

namespace App\Filament\Resources\Spents\Pages;

use App\Filament\Resources\Spents\SpentResource;
use App\Models\Transaction;
use Filament\Resources\Pages\CreateRecord;

class CreateSpent extends CreateRecord
{
    protected static string $resource = SpentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
