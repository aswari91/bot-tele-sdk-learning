<?php

namespace App\Filament\Resources\Tagihans\Widgets;

use App\Filament\Resources\Tagihans\Pages\ListTagihans;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class TagihanOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListTagihans::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Tagihan Belum Lunas',
                $this->getPageTableQuery()
                    ->where('lunas', false)
                    ->count()
            )
                ->icon('heroicon-m-paper-airplane')
                ->description('Tagihan yang belum dibayar')
                ->color('danger'),
            Stat::make(
                'Total Sisa Tagihan',
                'IDR ' . number_format(
                    $this->getPageTableQuery()
                        ->where('lunas', false)
                        ->where('sisa_tagihan', '>=', 0)
                        ->sum('sisa_tagihan'),
                    2,
                    ',',
                    '.'
                )
            )
                ->description('Total tagihan belum dibayar')
                ->color('danger'),
            Stat::make('Jumlah tagihan', $this->getPageTableQuery()->count())
                ->description('Jumlah keseluruhan tagihan')
                ->color('success'),
        ];
    }
}
