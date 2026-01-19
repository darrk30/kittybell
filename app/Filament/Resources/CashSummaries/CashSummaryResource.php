<?php

namespace App\Filament\Resources\CashSummaries;

use App\Filament\Resources\CashSummaries\Pages\CreateCashSummary;
use App\Filament\Resources\CashSummaries\Pages\EditCashSummary;
use App\Filament\Resources\CashSummaries\Pages\ListCashSummaries;
use App\Filament\Resources\CashSummaries\RelationManagers\TransactionsRelationManager;
use App\Filament\Resources\CashSummaries\Schemas\CashSummaryForm;
use App\Filament\Resources\CashSummaries\Tables\CashSummariesTable;
use App\Models\CashSummary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CashSummaryResource extends Resource
{
    protected static ?string $model = CashSummary::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static string | UnitEnum | null $navigationGroup = 'Finanzas';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Resúmenes de efectivo';

    protected static ?string $pluralModelLabel = 'Resúmenes de efectivo';

    public static function form(Schema $schema): Schema
    {
        return CashSummaryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashSummariesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TransactionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCashSummaries::route('/'),
            'create' => CreateCashSummary::route('/create'),
            'edit' => EditCashSummary::route('/{record}/edit'),
        ];
    }
}
