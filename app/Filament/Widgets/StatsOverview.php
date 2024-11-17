<?php

namespace App\Filament\Widgets;

use App\Models\BarangMaster;
use App\Models\BarangTransaksi;
use App\Models\Faskes;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;


class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();
        return [
            Stat::make('Barang Masuk', BarangMaster::query()->count())
            ->description('Jumlah Barang Masuk')
            ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
            ->color('success'),
        Stat::make('Barang Transaksi', BarangTransaksi::query()->count())
            ->description('Jumlah Barang Transaksi')
            ->descriptionIcon('heroicon-m-arrow-trending-down', IconPosition::Before)
            ->color('danger'),
        Stat::make('Faskes', Faskes::query()->count())
            ->description('Jumlah Faskes ')
            ->color('success'),
        ];
    }
}
