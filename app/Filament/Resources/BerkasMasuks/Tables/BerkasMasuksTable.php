<?php

namespace App\Filament\Resources\BerkasMasuks\Tables;

use App\Filament\Exports\BerkasMasukExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class BerkasMasuksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip')
                    ->label('NIP Pegawai')
                    ->placeholder('Tidak untuk pegawai')
                    ->searchable(),

                TextColumn::make('nomor_berkas')
                    ->label('Nomor Berkas')
                    ->badge()
                    ->color('success')
                    ->searchable(),

                TextColumn::make('perihal')
                    ->label('Perihal')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->perihal),

                TextColumn::make('asal_berkas')
                    ->label('Asal Berkas')
                    ->searchable(),

                TextColumn::make('tgl_berkas')
                    ->label('Tanggal Berkas')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('tgl_agenda')
                    ->label('Tanggal Agenda')
                    ->date('d M Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('nama_penyimpan')
                    ->label('Nama Penyimpan')
                    ->icon(Heroicon::User)
                    ->searchable(),

                TextColumn::make('locate')
                    ->label('Berkas')
                    ->getStateUsing(fn($record) => 'Lihat Dokumen')
                    ->url(fn($record) => url("/storage/{$record->locate}"), shouldOpenInNewTab: true)
                    ->color('primary')
                    ->icon('heroicon-o-document-text'),

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
            ->headerActions([
                ExportAction::make()
                    ->color('success')
                    ->icon(Heroicon::ArrowUpTray)
                    ->exporter(BerkasMasukExporter::class)
            ])
            ->recordActions(
                [
                    DeleteAction::make()
                        ->action(function ($record) {
                            if ($record->locate) {
                                Storage::disk('public')->delete($record->locate);
                            }

                            $record->delete();
                        }),
                    ViewAction::make('Detail')
                        ->color('success')
                        ->icon(Heroicon::Eye)
                ]
            )
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {

                                if ($record->locate) {
                                    Storage::disk('public')->delete($record->locate);
                                }

                                $record->delete();
                            }
                        }),
                    ExportBulkAction::make()
                        ->color('success')
                        ->icon(Heroicon::ArrowUpTray)
                        ->exporter(BerkasMasukExporter::class)
                ]),
            ]);
    }
}
