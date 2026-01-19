<?php

namespace App\Filament\Resources\CashSummaries\Pages;

use App\Filament\Resources\CashSummaries\CashSummaryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCashSummaries extends ListRecords
{
    protected static string $resource = CashSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
