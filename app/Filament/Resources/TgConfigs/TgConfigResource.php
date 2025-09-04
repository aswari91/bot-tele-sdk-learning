<?php

namespace App\Filament\Resources\TgConfigs;

use App\Filament\Resources\TgConfigs\Pages\CreateTgConfig;
use App\Filament\Resources\TgConfigs\Pages\EditTgConfig;
use App\Filament\Resources\TgConfigs\Pages\ListTgConfigs;
use App\Filament\Resources\TgConfigs\Pages\ViewTgConfig;
use App\Filament\Resources\TgConfigs\Schemas\TgConfigForm;
use App\Filament\Resources\TgConfigs\Schemas\TgConfigInfolist;
use App\Filament\Resources\TgConfigs\Tables\TgConfigsTable;
use App\Models\TgConfig;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TgConfigResource extends Resource
{
    protected static ?string $model = TgConfig::class;

    protected static ?string $modelLabel = 'Telegram Config';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'key';

    protected static string | UnitEnum | null $navigationGroup = 'Config';

    protected static ?string $navigationLabel = 'Telegram Config';

    protected static ?string $breadcrumb = 'Telegram Config';

    public static function form(Schema $schema): Schema
    {
        return TgConfigForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TgConfigInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TgConfigsTable::configure($table);
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
            'index' => ListTgConfigs::route('/'),
            'create' => CreateTgConfig::route('/create'),
            'view' => ViewTgConfig::route('/{record}'),
            'edit' => EditTgConfig::route('/{record}/edit'),
        ];
    }
}
