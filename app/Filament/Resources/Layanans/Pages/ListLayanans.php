<?php

namespace App\Filament\Resources\Layanans\Pages;

use App\Filament\Resources\Layanans\LayananResource;
use App\Models\Layanan;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLayanans extends ListRecords
{
    protected static string $resource = LayananResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make()
                ->badge(Layanan::count()),

            'belum kembali' => Tab::make()
                ->badge(Layanan::whereNull('kembali')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->whereNull('kembali')),

            'berkas kembali' => Tab::make()
                ->badge(Layanan::whereNotNull('kembali')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->whereNotNull('kembali')),
        ];
    }
}
