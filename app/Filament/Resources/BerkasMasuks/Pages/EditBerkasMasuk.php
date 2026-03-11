<?php

namespace App\Filament\Resources\BerkasMasuks\Pages;

use App\Filament\Resources\BerkasMasuks\BerkasMasukResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBerkasMasuk extends EditRecord
{
    protected static string $resource = BerkasMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
