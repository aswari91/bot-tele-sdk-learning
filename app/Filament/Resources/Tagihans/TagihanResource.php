<?php

namespace App\Filament\Resources\Tagihans;

use BackedEnum;
use App\Models\Tagihan;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Tagihans\Pages\EditTagihan;
use App\Filament\Resources\Tagihans\Pages\ViewTagihan;
use App\Filament\Resources\Tagihans\Pages\ListTagihans;
use App\Filament\Resources\Tagihans\Pages\CreateTagihan;
use App\Filament\Resources\Tagihans\Schemas\TagihanForm;
use App\Filament\Resources\Tagihans\Tables\TagihansTable;
use App\Filament\Resources\Tagihans\Schemas\TagihanInfolist;
use App\Filament\Resources\Tagihans\Widgets\TagihanOverview;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Tagihan';

    protected static ?string $label = 'Tagihan Credit Card';

    public static function form(Schema $schema): Schema
    {
        return TagihanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TagihanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TagihansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'bayarTagihans' => RelationManagers\BayarTagihansRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TagihanOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTagihans::route('/'),
            'create' => CreateTagihan::route('/create'),
            'view' => ViewTagihan::route('/{record}'),
            'edit' => EditTagihan::route('/{record}/edit'),
        ];
    }
}
