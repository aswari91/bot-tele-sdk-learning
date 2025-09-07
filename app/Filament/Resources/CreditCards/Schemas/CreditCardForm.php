<?php

namespace App\Filament\Resources\CreditCards\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CreditCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('card_number')
                    ->mask(\Filament\Support\RawJs::make(<<<'JS'
                       '9999 9999 9999 9999'
                    JS))
                    ->required(),
                TextInput::make('card_name')
                    ->required(),
                Select::make('card_type')
                    ->searchable()
                    ->options([
                        'Visa' => 'Visa',
                        'MasterCard' => 'MasterCard',
                        'American Express' => 'American Express',
                        'JCB' => 'JCB',
                        'Other' => 'Other',
                    ])
                    ->required(),
                TextInput::make('due_date')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(31),
                TextInput::make('closing_date')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(31),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
