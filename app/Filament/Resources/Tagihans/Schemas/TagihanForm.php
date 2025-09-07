<?php

namespace App\Filament\Resources\Tagihans\Schemas;

use Filament\Support\RawJs;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class TagihanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('credit_card_id')
                    ->reactive()
                    ->searchable()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            $creditCard = \App\Models\CreditCard::find($state);
                            if ($creditCard) {
                                $creditCard->due_date ? $set('jatuh_tempo', now()->startOfMonth()->addMonth()->setDay($creditCard->due_date)->format('Y-m-d')) : null;
                            }
                        }
                    })
                    ->relationship('creditCard', 'nama_kartu', fn(Builder $query) => $query->where('user_id', auth()->id())->where('is_active', true))
                    ->required(),
                TextInput::make('total_tagihan')
                    ->prefix('Rp ')
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if (!$state) {
                            $set('total_tagihan', '');
                        }
                    })
                    ->suffixAction(Action::make('clear')
                        ->icon('heroicon-o-x-circle')
                        ->size('sm')
                        ->action(function (callable $set) {
                            $set('total_tagihan', '');
                        }))
                    ->mask(RawJs::make('$money($input)'))
                    ->required(),
                DatePicker::make('jatuh_tempo')
                    ->required(),
            ]);
    }
}
