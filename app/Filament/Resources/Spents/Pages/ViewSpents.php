<?php

namespace App\Filament\Resources\Spents\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use App\Filament\Resources\Spents\SpentResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSpents extends ViewRecord
{
    protected static string $resource = SpentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }

    /**
     * Personaliza el título de la vista
     */
    public function getTitle(): string
    {
        $record = $this->getRecord();
        return "Gasto N° {$record->transaction_code}";
    }
}
