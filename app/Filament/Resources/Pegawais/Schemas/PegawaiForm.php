<?php

namespace App\Filament\Resources\Pegawais\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nip')
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('thn_angkat')
                    ->required()
                    ->numeric(),
                DatePicker::make('tgl_lahir')
                    ->required(),
                Select::make('jenis_kelamin')
                    ->options(['laki-laki' => 'Laki laki', 'perempuan' => 'Perempuan'])
                    ->required(),
                TextInput::make('unit_pegawai_id')
                    ->required()
                    ->numeric(),
                Select::make('sts_pegawai')
                    ->options(['tendik' => 'Tendik', 'dosen' => 'Dosen'])
                    ->required(),
                TextInput::make('nomor_berkas')
                    ->required(),
                TextInput::make('lemari')
                    ->required()
                    ->numeric(),
                TextInput::make('hambalan')
                    ->required()
                    ->numeric(),
                Select::make('jenis_pegawai')
                    ->options([
            'Pns' => 'Pns',
            'Non Pns Tetap' => 'Non pns tetap',
            'Non Pns Kontrak' => 'Non pns kontrak',
            'Pensiun' => 'Pensiun',
        ])
                    ->required(),
                TextInput::make('masa_kerja')
                    ->required()
                    ->numeric(),
            ]);
    }
}
