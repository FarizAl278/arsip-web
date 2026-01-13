<?php

namespace App\Filament\Resources\Pegawais\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                ]),
            ]);
    }
}
