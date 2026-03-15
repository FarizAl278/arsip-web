<?php

namespace App\Filament\Forms;

use App\Models\Unit;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class PegawaiDetail
{
    public static function schema(): array
    {
        return [
            Section::make('Informasi Pribadi')
                ->description('Data dasar identitas pegawai')
                ->icon('heroicon-o-user')
                ->columns(2)
                ->schema([
                    TextInput::make('nip')
                        ->label('NIP')
                        ->required()
                        ->columnSpan(1),
                    TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required()
                        ->columnSpan(1),
                    DatePicker::make('tgl_lahir')
                        ->label('Tanggal Lahir')
                        ->required()
                        ->columnSpan(1),
                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan'  => 'Perempuan',
                        ])
                        ->required()
                        ->columnSpan(1),
                ])
                ->columnSpanFull(),

            Section::make('Status Kepegawaian')
                ->description('Informasi posisi dan jenis pegawai')
                ->icon('heroicon-o-briefcase')
                ->columns(2)
                ->schema([
                    Select::make('sts_pegawai')
                        ->label('Status Pegawai')
                        ->options([
                            'tendik' => 'Tendik',
                            'dosen'  => 'Dosen',
                        ])
                        ->required(),
                    Select::make('jenis_pegawai')
                        ->label('Jenis Pegawai')
                        ->options([
                            'Pns'             => 'PNS',
                            'Non Pns Tetap'   => 'Non PNS Tetap',
                            'Non Pns Kontrak' => 'Non PNS Kontrak',
                            'Pensiun'         => 'Pensiun',
                        ])
                        ->live()
                        ->required(),

                    Select::make('unit_pegawai')
                        ->label('Unit Pegawai')
                        ->options(Unit::pluck('name', 'name'))
                        ->searchable()
                        ->required(),

                    TextInput::make('thn_angkat')
                        ->label('Tahun Pengangkatan')
                        ->required()
                        ->placeholder("co: 1980")
                        ->numeric(),
                    TextInput::make('masa_kerja')
                        ->label('Masa Kerja (Tahun)')
                        ->required()
                        ->placeholder("co: 32")
                        ->numeric(),

                    DatePicker::make('TMT')
                        ->label('TMT'),

                    DatePicker::make('tgl_pensiun')
                        ->label('Tanggal Pensiun'),
                ])
                ->columnSpanFull(),

            Section::make('Lokasi Berkas')
                ->description('Informasi penyimpanan dokumen fisik')
                ->icon('heroicon-o-archive-box')
                ->columns(3)
                ->schema([
                    TextInput::make('nomor_berkas')
                        ->label('Nomor Berkas')
                        ->required(),
                    TextInput::make('lemari')
                        ->label('Lemari')
                        ->required()
                        ->numeric(),
                    TextInput::make('hambalan')
                        ->label('Hambalan')
                        ->required()
                        ->numeric(),
                ])
                ->columnSpanFull(),
        ];
    }
}
