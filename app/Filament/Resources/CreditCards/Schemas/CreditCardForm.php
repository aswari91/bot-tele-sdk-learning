<?php

namespace App\Filament\Resources\CreditCards\Schemas;

use Filament\Support\RawJs;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;

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
                TextInput::make('total_anualfee')
                    ->prefix('Rp ')
                    ->suffixAction(Action::make('clear')
                        ->icon('heroicon-o-x-circle')
                        ->size('sm')
                        ->action(function (callable $set) {
                            $set('total_anualfee', '');
                        }))
                    ->mask(RawJs::make('$money($input)'))
                    ->required(),
                Toggle::make('have_anualfee')
                    ->label('Have Annual Fee')
                    ->default(false),
            ]);
    }
}
