<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Enums\MovementStockType;
use App\Enums\StateProduct;
use App\Models\MovementStock;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;

class PresentationRelationManager extends RelationManager
{
    protected static string $relationship = 'presentations';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(255),

            // TextInput::make('price')
            //     ->label('Precio ext')
            //     ->numeric()
            //     ->prefix('S/.'),
            // TextInput::make('stock')
            //     ->label('Stock')
            //     ->numeric()
            //     ->disabled(fn($record) => $record && $record->exists),

            FileUpload::make('image')
                ->label('Imagen')
                ->image()
                ->disk('public')
                ->directory('products/presentations/')
                ->visibility('public')
                ->preserveFilenames(),

            Select::make('is_active')
                ->label('Estado')
                ->options([
                    StateProduct::Activo->value => StateProduct::Activo->getLabel(),
                    StateProduct::Inactivo->value => StateProduct::Inactivo->getLabel(),
                ])
                ->default(StateProduct::Activo->value)
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),

                // 🔹 Estado con colores
                TextColumn::make('is_active')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),

                // 🗂️ Acción de archivar
                // Action::make('archive')
                //     ->label('Archivar')
                //     ->icon('heroicon-o-archive-box')
                //     ->color('warning')
                //     ->requiresConfirmation()
                //     ->action(function ($record) {
                //         $record->update(['is_active' => StateProduct::Archivado->value]);

                //         Notification::make()
                //             ->title('Presentación archivada')
                //             ->body('La presentación ha sido archivada correctamente. Ya no estará activa en ventas.')
                //             ->success()
                //             ->send();
                //     }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
