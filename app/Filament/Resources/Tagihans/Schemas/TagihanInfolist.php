<?php

namespace App\Filament\Resources\Tagihans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TagihanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('credit_card_id')
                    ->numeric(),
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('total_tagihan')
                    ->numeric(),
                TextEntry::make('tagihan_terbayar')
                    ->numeric(),
                TextEntry::make('sisa_tagihan')
                    ->numeric(),
                TextEntry::make('jatuh_tempo')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
