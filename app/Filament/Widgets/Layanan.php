<?php

namespace App\Filament\Widgets;

use App\Models\BerkasMasuk;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class Layanan extends ChartWidget
{
    protected ?string $heading = 'Berkas Masuk per Bulan';
    protected ?string $description = 'Distribusi berkas masuk dalam tahun';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $tahunSekarang = now()->year;

        $data = BerkasMasuk::select(
                DB::raw('MONTH(tgl_agenda) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('tgl_agenda', $tahunSekarang)
            ->groupBy(DB::raw('MONTH(tgl_agenda)'))
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $bulanLabel = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                       'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $totals = [];
        for ($i = 1; $i <= 12; $i++) {
            $totals[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Berkas',
                    'data' => $totals,
                    'backgroundColor' => '#378ADD',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $bulanLabel,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}