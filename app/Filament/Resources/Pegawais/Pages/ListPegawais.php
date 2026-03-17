<?php

namespace App\Filament\Resources\Pegawais\Pages;

use App\Filament\Resources\Pegawais\PegawaiResource;
use App\Models\Pegawai;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPegawais extends ListRecords
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }


    public function getTabs(): array
    {
        $counts = Pegawai::selectRaw('jenis_pegawai, COUNT(*) as total')
            ->groupBy('jenis_pegawai')
            ->pluck('total', 'jenis_pegawai');

        return [
            'semua' => Tab::make()
                ->badge(array_sum($counts->toArray()))
                ->badgeColor('gray'),

            'PNS' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('jenis_pegawai', 'Pns'))
                ->badge($counts['Pns'] ?? 0)
                ->badgeColor('success'),

            'Non PNS Tetap' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('jenis_pegawai', 'Non Pns Tetap'))
                ->badge($counts['Non Pns Tetap'] ?? 0)
                ->badgeColor('primary'),

            'Non PNS Kontrak' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('jenis_pegawai', 'Non Pns Kontrak'))
                ->badge($counts['Non Pns Kontrak'] ?? 0)
                ->badgeColor('warning'),

            'Pensiun' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('jenis_pegawai', 'Pensiun'))
                ->badge($counts['Pensiun'] ?? 0)
                ->badgeColor('danger'),
        ];
    }
}
