<?php

namespace App\Filament\Resources\Layanans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LayanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_berkas')
                    ->label('Kode Berkas')
                    ->searchable(),

                TextColumn::make('status_berkas')
                    ->label('Status Berkas')
                    ->searchable()
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Pinjam Berkas'      => 'warning',
                        'Fotocopy Berkas'    => 'info',
                        'Lihat Ditempat'     => 'success',
                        'Download File Scan' => 'primary',
                        'Layanan Lainnya'    => 'gray',
                        default              => 'gray',
                    }),

                TextColumn::make('berkas_layanan')
                    ->label('Cakupan Berkas')
                    ->searchable(),

                TextColumn::make('berkas_lain')
                    ->label('Spesifikasi Berkas')
                    ->searchable()
                    ->limit(25)
                    ->tooltip(fn ($record) => $record->berkas_lain)
                    ->placeholder('-'),

                TextColumn::make('nama')
                    ->label('Nama Peminjam')
                    ->searchable(),

                TextColumn::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->searchable(),

                TextColumn::make('subdit')
                    ->label('Subdit')
                    ->searchable(),

                TextColumn::make('seksi')
                    ->label('Seksi')
                    ->searchable(),

                TextColumn::make('internal')
                    ->label('Internal SDM')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Ya'    => 'success',
                        'Tidak' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('tanggal')
                    ->label('Tanggal Pinjam')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('kembali')
                    ->label('Tanggal Kembali')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('Belum Kembali'),

                TextColumn::make('operator')
                    ->label('Operator')
                    ->searchable(),

                TextColumn::make('sifat_layanan')
                    ->label('Sifat Layanan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sifat_lain')
                    ->label('Keterangan Layanan')
                    ->searchable()
                    ->limit(25)
                    ->tooltip(fn ($record) => $record->sifat_lain)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('jenis_pegawai')
                    ->label('Jenis Pegawai')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('keluar')
                    ->label('Keluar')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}