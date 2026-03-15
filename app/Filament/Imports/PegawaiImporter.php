<?php

namespace App\Filament\Imports;

use App\Models\Pegawai;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class PegawaiImporter extends Importer
{
    protected static ?string $model = Pegawai::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nip')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('thn_angkat')
                ->numeric(),
            ImportColumn::make('tgl_lahir')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('jenis_kelamin')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('unit_pegawai')
                ->rules(['max:255']),
            ImportColumn::make('sts_pegawai'),
            ImportColumn::make('nomor_berkas')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('lemari')
                ->numeric(),
            ImportColumn::make('hambalan')
                ->numeric(),
            ImportColumn::make('jenis_pegawai')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('TMT'),
            ImportColumn::make('tgl_pensiun'),
            ImportColumn::make('masa_kerja')
                ->numeric(),
        ];
    }

    public function resolveRecord(): Pegawai
    {
        return Pegawai::firstOrNew([
            'nip' => $this->data['nip'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your pegawai import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
