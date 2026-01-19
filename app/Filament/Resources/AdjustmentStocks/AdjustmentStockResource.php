<?php

namespace App\Filament\Resources\AdjustmentStocks;

use App\Filament\Resources\AdjustmentStocks\Pages\CreateAdjustmentStock;
use App\Filament\Resources\AdjustmentStocks\Pages\EditAdjustmentStock;
use App\Filament\Resources\AdjustmentStocks\Pages\ListAdjustmentStocks;
use App\Filament\Resources\AdjustmentStocks\Schemas\AdjustmentStockForm;
use App\Filament\Resources\AdjustmentStocks\Tables\AdjustmentStocksTable;
use App\Models\AdjustmentStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AdjustmentStockResource extends Resource
{
    protected static ?string $model = AdjustmentStock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::WrenchScrewdriver;

    protected static string | UnitEnum | null $navigationGroup = 'Inventario';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'motive';

    protected static ?string $navigationLabel = 'Ajustes de stock';

    protected static ?string $pluralModelLabel = 'Ajustes de stock';

    public static function form(Schema $schema): Schema
    {
        return AdjustmentStockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdjustmentStocksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdjustmentStocks::route('/'),
            'create' => CreateAdjustmentStock::route('/create'),
            'edit' => EditAdjustmentStock::route('/{record}/edit'),
        ];
    }
}
