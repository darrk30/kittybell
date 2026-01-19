<?php

namespace App\Filament\Resources\Purchases\Widgets;

use App\Enums\StatePurchase;
use App\Filament\Resources\Purchases\Pages\ListPurchases;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PurchaseOverview extends StatsOverviewWidget
{

    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListPurchases::class;
    }

    protected function getStats(): array
    {
             // Obtenemos la query filtrada según la tabla
        $query = $this->getPageTableQuery()
            ->where('status', StatePurchase::Aceptado->value)
            ->where('document_type', '!=', 'Cotización'); // solo aceptadas

        return [
            Stat::make('Compras', $query->count())
                ->description('Cantidad de compras realizadas'),

            Stat::make('Total en compras', 'S/ ' . number_format($query->sum('total_amount'), 2))
                ->description('Suma de todos los montos de compras'),
        ];
    }
}
