<?php

namespace App\Filament\Resources\MetodePembayarans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class MetodePembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
