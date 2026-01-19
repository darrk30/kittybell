<?php

namespace App\Filament\Pages;

use App\Models\Presentation;
use App\Models\Product;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use UnitEnum;

class InventoryPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Inventario';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArchiveBox;

    protected static string | UnitEnum | null $navigationGroup = 'Inventario';

    protected static ?int $navigationSort = 5;
    protected string $view = 'filament.pages.inventory-page'; // ❌ no static

    protected function getTableQuery()
    {
        return Product::query();
    }

    protected function getTableColumns(): array
    {
        return [
            ImageColumn::make('image')->disk('public')->label('Imagen')->default(asset('images/default-product.png'))->square(),
            TextColumn::make('name')->label('Producto')->sortable(),
            TextColumn::make('stock')->label('Stock')->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [];
    }
}
