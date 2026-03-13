<?php

namespace App\Filament\Actions;

use App\Models\Layanan;
use App\Filament\Services\LayananService;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Collection;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class PinjamBerkasBulkAction
{
    public static function make(): BulkAction
    {
        return BulkAction::make('Pinjam Berkas')
            ->icon(Heroicon::ArchiveBoxArrowDown)
            ->color('primary')
            ->form([
                Section::make('Jenis Layanan')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Radio::make('status_berkas')
                            ->label('Jenis Layanan')
                            ->options([
                                'Pinjam Berkas'      => 'Pinjam Berkas',
                                'Fotocopy Berkas'    => 'Fotocopy Berkas',
                                'Lihat Ditempat'     => 'Lihat Ditempat',
                                'Download File Scan' => 'Download File Scan',
                                'Layanan Lainnya'    => 'Layanan Lainnya',
                            ])
                            ->required()
                            ->live(),

                        TextInput::make('sifat_lain')
                            ->label('Keterangan Layanan Lainnya')
                            ->placeholder('Tuliskan jenis layanan...')
                            ->required(fn($get) => $get('status_berkas') === 'Layanan Lainnya')
                            ->hidden(fn($get) => $get('status_berkas') !== 'Layanan Lainnya'),
                    ])
                    ->columnSpanFull(),

                Section::make('Berkas yang Diminta')
                    ->icon('heroicon-o-folder-open')
                    ->schema([
                        Radio::make('berkas_layanan')
                            ->label('Cakupan Berkas')
                            ->options([
                                '1 Bundel Berkas' => '1 Bundel Berkas',
                                'Berkas Tertentu' => 'Berkas Tertentu',
                            ])
                            ->required()
                            ->live(),

                        TextInput::make('berkas_lain')
                            ->label('Spesifikasi Berkas')
                            ->placeholder('Tuliskan berkas yang diminta...')
                            ->required(fn($get) => $get('berkas_layanan') === 'Berkas Tertentu')
                            ->hidden(fn($get) => $get('berkas_layanan') !== 'Berkas Tertentu'),
                    ])
                    ->columnSpanFull(),

                Section::make('Data Peminjam')
                    ->icon('heroicon-o-user')
                    ->columns(2)
                    ->schema([
                        Radio::make('internal')
                            ->label('Layanan Internal SDM?')
                            ->options([
                                'Ya'    => 'Ya',
                                'Tidak' => 'Tidak',
                            ])
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('nama')
                            ->label('Nama Peminjam')
                            ->required(),

                        TextInput::make('unit_kerja')
                            ->label('Unit Kerja')
                            ->required(),

                        TextInput::make('subdit')
                            ->label('Subdit')
                            ->required(),

                        TextInput::make('seksi')
                            ->label('Seksi')
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ])
            ->action(function (Collection $records, array $data) {
                LayananService::pinjamBerkas($records, $data);
            });
    }
}
