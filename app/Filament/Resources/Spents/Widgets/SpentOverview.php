<?php

namespace App\Filament\Resources\Spents\Widgets;

use App\Filament\Resources\Spents\Pages\ListSpents;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SpentOverview extends StatsOverviewWidget
{
    
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListSpents::class;
    }

    protected function getStats(): array
    {
        // Obtenemos la query filtrada según la tabla
        $query = $this->getPageTableQuery();
        return [
            Stat::make('Total de gastos', $query->count())
                ->description('Cantidad de ventas realizadas'),

            Stat::make('Monto total en gatos', 'S/ ' . number_format($query->sum('amount'), 2))
                ->description('Suma de todos los gastos'),
        ];
    }
}
