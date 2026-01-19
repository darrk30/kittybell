<?php

namespace App\Filament\Resources\DeliveryTypes;

use App\Filament\Resources\DeliveryTypes\Pages\CreateDeliveryType;
use App\Filament\Resources\DeliveryTypes\Pages\EditDeliveryType;
use App\Filament\Resources\DeliveryTypes\Pages\ListDeliveryTypes;
use App\Filament\Resources\DeliveryTypes\Schemas\DeliveryTypeForm;
use App\Filament\Resources\DeliveryTypes\Tables\DeliveryTypesTable;
use App\Models\DeliveryType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DeliveryTypeResource extends Resource
{
    protected static ?string $model = DeliveryType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::RocketLaunch;

    protected static string | UnitEnum | null $navigationGroup = 'Ajustes';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Deliverys';

    protected static ?string $pluralModelLabel = 'Deliverys';

    public static function form(Schema $schema): Schema
    {
        return DeliveryTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveryTypesTable::configure($table);
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
            'index' => ListDeliveryTypes::route('/'),
            // 'create' => CreateDeliveryType::route('/create'),
            // 'edit' => EditDeliveryType::route('/{record}/edit'),
        ];
    }
}
