<?php

namespace App\Filament\Resources\CashSummaries\Pages;

use App\Filament\Resources\CashSummaries\CashSummaryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCashSummary extends EditRecord
{
    protected static string $resource = CashSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //DeleteAction::make(),
        ];
    }
}
