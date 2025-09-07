<?php

namespace App\Filament\Resources\MetodePembayarans\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class MetodePembayaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('metode_pembayaran')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime('d M Y H:i'),
                TextEntry::make('updated_at')
                    ->dateTime('d M Y H:i'),
            ]);
    }
}
