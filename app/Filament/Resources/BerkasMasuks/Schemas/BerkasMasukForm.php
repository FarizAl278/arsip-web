<?php

namespace App\Filament\Resources\BerkasMasuks\Schemas;

use App\Models\Pegawai;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
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
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                return Pegawai::query()
                                    ->where('nama', 'like', "%{$search}%")
                                    ->orWhere('nip', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('nama', 'nip');
                            })
                            ->getOptionLabelUsing(function ($value) {
                                return Pegawai::where('nip', $value)->value('nama');
                            }),
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
                        FileUpload::make('locate')
                            ->label('Berkas')
                            ->downloadable()
                            ->disk('public')
                            ->directory('data_scan')
                            ->columnSpanFull()
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
