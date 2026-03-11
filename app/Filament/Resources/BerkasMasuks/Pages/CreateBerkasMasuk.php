<?php

namespace App\Filament\Resources\BerkasMasuks\Pages;

use App\Filament\Resources\BerkasMasuks\BerkasMasukResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBerkasMasuk extends CreateRecord
{
    protected static string $resource = BerkasMasukResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['nama_penyimpan'] = Auth::user()->name;

        return $data;
    }
}
