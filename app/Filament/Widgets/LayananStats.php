<?php

namespace App\Filament\Widgets;

use App\Models\Layanan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LayananStats extends StatsOverviewWidget
{
    protected function getColumns(): int|array|null
    {
        return 2;
    }

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $sudahKembali = Layanan::whereNotNull('kembali')->count();
        $belumKembali = Layanan::whereNull('kembali')->count();

        $total = $sudahKembali + $belumKembali;

        $persenSudah = $total > 0 ? ($sudahKembali / $total) * 100 : 0;
        $persenBelum = $total > 0 ? ($belumKembali / $total) * 100 : 0;

        return [

            Stat::make('Layanan Sudah Dikembalikan', $sudahKembali)
                ->description(number_format($persenSudah, 1) . '% dari total')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([
                    $sudahKembali,
                    $belumKembali,
                ]),

            Stat::make('Layanan Belum Dikembalikan', $belumKembali)
                ->description(number_format($persenBelum, 1) . '% dari total')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger')
                ->chart([
                    $belumKembali,
                    $sudahKembali,
                ]),

        ];
    }
}
