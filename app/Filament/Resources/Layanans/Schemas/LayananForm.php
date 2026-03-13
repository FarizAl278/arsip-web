<?php

namespace App\Filament\Resources\Layanans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LayananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_berkas')
                    ->required(),
                TextInput::make('status_berkas')
                    ->required(),
                TextInput::make('sifat_layanan'),
                TextInput::make('sifat_lain')
                    ->required()
                    ->default('tidak'),
                TextInput::make('berkas_layanan'),
                TextInput::make('berkas_lain')
                    ->required()
                    ->default('tidak'),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('subdit')
                    ->required(),
                TextInput::make('unit_kerja')
                    ->required(),
                TextInput::make('seksi')
                    ->required(),
                TextInput::make('operator')
                    ->required(),
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('keluar')
                    ->required()
                    ->default('PERSONAL'),
                DatePicker::make('kembali'),
                Select::make('internal')
                    ->options(['Ya' => 'Ya', 'Tidak' => 'Tidak'])
                    ->default('Ya')
                    ->required(),
                TextInput::make('jenis_pegawai')
                    ->required(),
            ]);
    }
}
