<?php

namespace App\Filament\Resources\MovementStocks\Widgets;

use App\Enums\MovementStockType;
use App\Filament\Resources\MovementStocks\Pages\ListMovementStocks;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MovementStockStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListMovementStocks::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $totalEntradas = (clone $query)
            ->whereIn('movement_type', [
                MovementStockType::Compra->value,
                MovementStockType::AjusteEntrada->value,
                MovementStockType::StockInicial->value,
            ])
            ->sum('quantity');

        $totalSalidas = (clone $query)
            ->whereIn('movement_type', [
                MovementStockType::Venta->value,
                MovementStockType::AjusteSalida->value,
            ])
            ->sum('quantity');

        $totalMovimientos = (clone $query)->count();

        return [
            Stat::make('Total Entradas', number_format($totalEntradas, 2))
                ->description('Unidades ingresadas')
                ->color('success'),

            Stat::make('Total Salidas', number_format($totalSalidas, 2))
                ->description('Unidades salidas')
                ->color('danger'),

            Stat::make('Total Movimientos', number_format($totalMovimientos, 0))
                ->description('Movimientos registrados')
                ->color('gray'),
        ];
    }
}
