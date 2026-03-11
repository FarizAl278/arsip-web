<?php

namespace App\Filament\Resources\BerkasMasuks;

use App\Filament\Resources\BerkasMasuks\Pages\CreateBerkasMasuk;
use App\Filament\Resources\BerkasMasuks\Pages\EditBerkasMasuk;
use App\Filament\Resources\BerkasMasuks\Pages\ListBerkasMasuks;
use App\Filament\Resources\BerkasMasuks\Schemas\BerkasMasukForm;
use App\Filament\Resources\BerkasMasuks\Tables\BerkasMasuksTable;
use App\Models\BerkasMasuk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BerkasMasukResource extends Resource
{
    protected static ?string $model = BerkasMasuk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Data Berkas Masuk';

    public static function form(Schema $schema): Schema
    {
        return BerkasMasukForm::configure($schema);
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return BerkasMasuksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBerkasMasuks::route('/'),
            'create' => CreateBerkasMasuk::route('/create'),
            'edit' => EditBerkasMasuk::route('/{record}/edit'),
        ];
    }
}
