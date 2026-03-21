<?php

namespace App\Filament\Widgets;

use App\Models\Pegawai;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class JenisPegawaiChart extends ChartWidget
{
    protected ?string $heading = 'Chart Jenis Pegawai';

    protected ?string $description = 'Distribusi jumlah pegawai berdasarkan jenis kepegawaian';
    protected static ?int $sort = 2;

    protected ?string $maxHeight = "285px";

    protected function getData(): array
    {
        $data = Pegawai::query()
            ->selectRaw('jenis_pegawai, COUNT(*) as total')
            ->groupBy('jenis_pegawai')
            ->pluck('total', 'jenis_pegawai')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Jumlah Pegawai',
                    'data'            => array_values($data),
                    'backgroundColor' => [
                        '#3b82f6', // PNS - biru
                        '#10b981', // Non PNS Tetap - hijau
                        '#f59e0b', // Non PNS Kontrak - kuning
                        '#ef4444', // Pensiun - merah
                    ],
                    'hoverOffset' => 4,
                    'hoverBorderWidth'     => 2,
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array|RawJs|null
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'hover' => [
                'mode' => 'nearest',
                'animationDuration' => 200,
            ],
            'animation' => [
                "animateScale" => true,
                "animateRotate"
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
