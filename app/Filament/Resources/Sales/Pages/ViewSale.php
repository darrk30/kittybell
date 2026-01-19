<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewSale extends ViewRecord
{
    protected static string $resource = SaleResource::class;

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
        return "Venta N° {$record->series}-{$record->correlative}";
    }
}
