<?php

namespace App\Filament\Resources\BerkasMasuks\Pages;

use App\Filament\Resources\BerkasMasuks\BerkasMasukResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBerkasMasuks extends ListRecords
{
    protected static string $resource = BerkasMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
