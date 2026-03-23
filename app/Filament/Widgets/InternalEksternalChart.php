<?php

namespace App\Filament\Widgets;

use App\Models\Layanan;
use Filament\Widgets\ChartWidget;

class InternalEksternalChart extends ChartWidget
{
    protected ?string $heading = 'Layanan Internal / Eksternal';

    protected static ?int $sort = 3;


    protected function getData(): array
    {
        $data = Layanan::selectRaw('internal, COUNT(*) as total')
            ->groupBy('internal')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Distribusi Layanan',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => [
                        '#10b981', // Hijau (Internal)
                        '#f59e0b', // Kuning (Eksternal)
                    ],
                ],
            ],
            'labels' => $data->pluck('internal'),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
