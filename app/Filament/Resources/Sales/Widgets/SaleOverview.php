<?php

namespace App\Filament\Resources\Sales\Widgets;

use App\Filament\Resources\Sales\Pages\ListSales;
use App\Models\Sale;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SaleOverview extends StatsOverviewWidget
{

    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListSales::class;
    }

    protected function getStats(): array
    {
        // Obtenemos la query filtrada según la tabla
        $query = $this->getPageTableQuery()
            ->where('status', \App\Enums\StateSale::Aceptado->value)
            ->where('document_type', '!=', 'Cotización');

        return [
            Stat::make('Total de ventas', $query->count())
                ->description('Cantidad de ventas realizadas'),

            Stat::make('Monto total en ventas', 'S/ ' . number_format($query->sum('total_amount'), 2))
                ->description('Suma de todos los montos de ventas'),
        ];
    }
}
