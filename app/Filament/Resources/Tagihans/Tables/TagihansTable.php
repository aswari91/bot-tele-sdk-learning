<?php

namespace App\Filament\Resources\Tagihans\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Count;
use Illuminate\Database\Eloquent\Builder;

class TagihansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('lunas')
                    ->label('Tagihan Lunas')
                    ->boolean(),
                TextColumn::make('CreditCard.nama_kartu')
                    ->label('Nomor Credit Card')
                    ->searchable(condition: true, isIndividual: true)
                    ->sortable(),
                TextColumn::make('total_tagihan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tagihan_terbayar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sisa_tagihan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jatuh_tempo')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\TernaryFilter::make('lunas')
                    ->label('Tagihan Lunas')
                    ->trueLabel('Sudah Lunas')
                    ->falseLabel('Belum Lunas'),
                \Filament\Tables\Filters\Filter::make('jatuh_tempo')
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('start_date')
                            ->default(now()->startOfMonth())
                            ->label('Dari tanggal'),
                        \Filament\Forms\Components\DatePicker::make('end_date')
                            ->default(now()->addMonth()->endOfMonth())
                            ->label('Hingga tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('jatuh_tempo', '>=', $date),
                            )
                            ->when(
                                $data['end_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('jatuh_tempo', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->where('user_id', auth()->id()));
    }
}
