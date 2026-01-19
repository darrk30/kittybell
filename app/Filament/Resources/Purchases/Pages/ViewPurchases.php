<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchases extends ViewRecord
{
    protected static string $resource = PurchaseResource::class;

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
        return "Compra N° {$record->series}-{$record->correlative}";
    }
}
