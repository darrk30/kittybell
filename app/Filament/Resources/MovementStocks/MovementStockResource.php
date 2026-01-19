<?php

namespace App\Filament\Resources\MovementStocks;

use App\Filament\Resources\MovementStocks\Pages\ListMovementStocks;
use App\Filament\Resources\MovementStocks\Tables\MovementStocksTable;
use App\Filament\Resources\MovementStocks\Widgets\MovementStockStatsOverview;
use App\Models\MovementStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MovementStockResource extends Resource
{
    protected static ?string $model = MovementStock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowPathRoundedSquare;
    
    protected static string | UnitEnum | null $navigationGroup = 'Inventario';

    protected static ?int $navigationSort = 7;

    // protected static ?string $recordTitleAttribute = 'series_correlative';

    protected static ?string $navigationLabel = 'Movimientos Stock';

    protected static ?string $pluralModelLabel = 'Movimientos Stock';

    // public static function form(Schema $schema): Schema
    // {
    //     return MovementStockForm::configure($schema);
    // }

    public static function table(Table $table): Table
    {
        return MovementStocksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            MovementStockStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMovementStocks::route('/'),
        ];
    }
}
