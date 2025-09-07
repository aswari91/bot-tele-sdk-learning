<?php

namespace App\Filament\Resources\CreditCards;

use App\Filament\Resources\CreditCards\Pages\CreateCreditCard;
use App\Filament\Resources\CreditCards\Pages\EditCreditCard;
use App\Filament\Resources\CreditCards\Pages\ListCreditCards;
use App\Filament\Resources\CreditCards\Pages\ViewCreditCard;
use App\Filament\Resources\CreditCards\Schemas\CreditCardForm;
use App\Filament\Resources\CreditCards\Schemas\CreditCardInfolist;
use App\Filament\Resources\CreditCards\Tables\CreditCardsTable;
use App\Models\CreditCard;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CreditCardResource extends Resource
{
    protected static ?string $model = CreditCard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static ?string $recordTitleAttribute = 'CreditCard';

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return CreditCardForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CreditCardInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CreditCardsTable::configure($table);
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
            'index' => ListCreditCards::route('/'),
            'create' => CreateCreditCard::route('/create'),
            'view' => ViewCreditCard::route('/{record}'),
            'edit' => EditCreditCard::route('/{record}/edit'),
        ];
    }
}
