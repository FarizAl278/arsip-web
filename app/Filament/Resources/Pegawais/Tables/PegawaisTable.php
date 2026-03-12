<?php

namespace App\Filament\Resources\Pegawais\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PegawaisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip')
                    ->icon(Heroicon::ClipboardDocument)
                    ->copyable()
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('thn_angkat')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tgl_lahir')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jenis_kelamin')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('unit_pegawai_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sts_pegawai')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nomor_berkas')
                    ->searchable(),
                TextColumn::make('lemari')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('hambalan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jenis_pegawai')
                    ->badge(),
                TextColumn::make('masa_kerja')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('jenis_pegawai')
                    ->options([
                        'Pns' => 'PNS',
                        'Non Pns Tetap' => 'Non PNS Tetap',
                        'Non Pns Kontrak' => 'Non PNS Kontrak',
                        'Pensiun' => 'Pensiun',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('Pinjam Berkas')
                        ->icon(Heroicon::ArchiveBoxArrowDown)
                        ->color('primary')
                        ->form([
                            Radio::make('status_berkas')
                                ->options([
                                    'Pinjam Berkas' => 'Pinjam Berkas',
                                    'Fotocopy Berkas' => 'Fotocopy Berkas',
                                    'Lihat Ditempat' => 'Lihat Ditempat',
                                    'Download File Scan' => 'Download File Scan',
                                    'Layanan Lainnya' => 'Layanan Lainnya',
                                ])
                                ->required()
                                ->live(),

                            TextInput::make('sifat_lain')
                                ->label('Layanan Lainnya')
                                ->required(fn($get) => $get('status_berkas') === 'Layanan Lainnya')
                                ->visible(fn($get) => $get('status_berkas') === 'Layanan Lainnya'),

                            Radio::make('berkas_layanan')
                                ->options([
                                    '1 Bundel Berkas' => '1 Bundel Berkas',
                                    'Berkas Tertentu' => 'Berkas Tertentu',
                                ])
                                ->required()
                                ->live(),

                            TextInput::make('berkas_lain')
                                ->label('Berkas Lain')
                                ->required(fn($get) => $get('berkas_layanan') === 'Berkas Tertentu')
                                ->visible(fn($get) => $get('berkas_layanan') === 'Berkas Tertentu'),
                        ])
                ]),
            ]);
    }
}
