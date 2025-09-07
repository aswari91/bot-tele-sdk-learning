<?php

namespace App\Filament\Resources\CreditCards\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CreditCardInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id'),
                TextEntry::make('card_number'),
                TextEntry::make('card_name'),
                TextEntry::make('card_type'),
                TextEntry::make('due_date')
                    ->date(),
                TextEntry::make('closing_date')
                    ->date(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
