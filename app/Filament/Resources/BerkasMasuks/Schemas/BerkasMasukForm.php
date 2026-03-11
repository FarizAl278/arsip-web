<?php

namespace App\Filament\Resources\BerkasMasuks\Schemas;

use App\Models\Pegawai;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BerkasMasukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pegawai')
                    ->description('Kaitkan berkas dengan pegawai terkait')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Select::make('nip')
                            ->label('Pegawai (Opsional)')
                            ->options(Pegawai::pluck('nama', 'nip'))
                            ->searchable(),
                    ])
                    ->columnSpanFull(),

                Section::make('Detail Berkas')
                    ->description('Informasi utama mengenai berkas')
                    ->icon('heroicon-o-document-text')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nomor_berkas')
                            ->label('Nomor Berkas')
                            ->required(),
                        TextInput::make('perihal')
                            ->label('Perihal')
                            ->required(),
                        TextInput::make('asal_berkas')
                            ->label('Asal Berkas')
                            ->required(),
                        TextInput::make('tahun')
                            ->label('Tahun')
                            ->required()
                            ->numeric(),
                    ])
                    ->columnSpanFull(),

                Section::make('Tanggal')
                    ->description('Informasi waktu berkas')
                    ->icon('heroicon-o-calendar')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('tgl_berkas')
                            ->label('Tanggal Berkas')
                            ->required(),
                        DatePicker::make('tgl_agenda')
                            ->label('Tanggal Agenda')
                            ->required(),
                    ])
                    ->columnSpanFull(),

                Section::make('Penyimpanan')
                    ->description('Informasi lokasi fisik berkas')
                    ->icon('heroicon-o-archive-box')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_penyimpan')
                            ->label('Nama Penyimpan')
                            ->required(),
                        TextInput::make('locate')
                            ->label('Lokasi')
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
