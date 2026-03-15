<?php

namespace App\Filament\Resources\Pegawais\Schemas;

use App\Models\Unit;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                            ->label('TMT')
                            ->visible(fn(Get $get) => in_array($get('jenis_pegawai'), ['Non Pns Tetap', 'Non Pns Kontrak']))
                            ->required(fn(Get $get) => in_array($get('jenis_pegawai'), ['Non Pns Tetap', 'Non Pns Kontrak'])),

                        DatePicker::make('tgl_pensiun')
                            ->label('Tanggal Pensiun')
                            ->visible(fn(Get $get) => $get('jenis_pegawai') === 'Pensiun')
                            ->required(fn(Get $get) => $get('jenis_pegawai') === 'Pensiun'),
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
            ]);
    }
}
