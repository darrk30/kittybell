<?php

namespace App\Filament\Resources\Spents;

use App\Filament\Resources\Spents\Pages\CreateSpent;
use App\Filament\Resources\Spents\Pages\EditSpent;
use App\Filament\Resources\Spents\Pages\ListSpents;
use App\Filament\Resources\Spents\Pages\ViewSpents;
use App\Filament\Resources\Spents\Schemas\SpentForm;
use App\Filament\Resources\Spents\Tables\SpentsTable;
use App\Filament\Resources\Spents\Widgets\SpentOverview;
use App\Models\Spent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SpentResource extends Resource
{
    protected static ?string $model = Spent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ReceiptRefund;

    protected static string | UnitEnum | null $navigationGroup = 'Finanzas';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Gastos';

    protected static ?string $pluralModelLabel = 'Gastos';

    public static function form(Schema $schema): Schema
    {
        return SpentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpentsTable::configure($table);
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
            SpentOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpents::route('/'),
            'create' => CreateSpent::route('/create'),
            'edit' => EditSpent::route('/{record}/edit'),
            'view' => ViewSpents::route('/{record}/view'),
        ];
    }
}
