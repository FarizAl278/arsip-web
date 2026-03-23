<?php

namespace App\Filament\Widgets;

use App\Models\Layanan;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class LayananChart extends ChartWidget
{
    protected ?string $heading = 'Layanan per Bulan';

    protected static ?int $sort = 3;

    protected function getFilters(): ?array
    {
        $tahunList = [];
        $tahunMulai = 2022;
        $tahunSekarang = now()->year;

        for ($t = $tahunSekarang; $t >= $tahunMulai; $t--) {
            $tahunList[$t] = (string) $t;
        }

        return $tahunList;
    }

    protected function getData(): array
    {
        // $this->filter otomatis terisi dari pilihan user
        $tahun = $this->filter ?? now()->year;

        $data = Layanan::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('tanggal', $tahun)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $totals = [];
        for ($i = 1; $i <= 12; $i++) {
            $totals[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Jumlah Layanan',
                    'data'            => $totals,
                    'backgroundColor' => '#f59e0b',
                    'borderRadius'    => 6,
                    'borderWidth' => 0
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
