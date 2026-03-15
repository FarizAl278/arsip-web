<?php

namespace App\Filament\Imports;

use App\Models\Pegawai;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
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

    /**
     * Error handling per row
     */
    protected function beforeSave(): void
    {
        // Validasi wajib
        if (empty($this->data['nip']) || empty($this->data['nama'])) {
            throw new RowImportFailedException(
                'NIP dan Nama wajib diisi.'
            );
        }

        // Validasi jenis kelamin
        if (!in_array($this->data['jenis_kelamin'], ['Laki-laki', 'Perempuan'])) {
            throw new RowImportFailedException(
                "Jenis kelamin harus 'Laki-laki' atau 'Perempuan'."
            );
        }

        // Validasi status pegawai
        if (!empty($this->data['sts_pegawai'])) {
            if (!in_array($this->data['sts_pegawai'], ['tendik', 'dosen'])) {
                throw new RowImportFailedException(
                    "Status pegawai hanya boleh 'tendik' atau 'dosen'."
                );
            }
        }

        // Validasi jenis pegawai
        if (!in_array($this->data['jenis_pegawai'], [
            'Pns',
            'Non Pns Tetap',
            'Non Pns Kontrak',
            'Pensiun'
        ])) {
            throw new RowImportFailedException(
                "Jenis pegawai tidak valid: {$this->data['jenis_pegawai']}."
            );
        }

        // Validasi nomor berkas unik
        if (Pegawai::where('nomor_berkas', $this->data['nomor_berkas'])
            ->where('nip', '!=', $this->data['nip'])
            ->exists()
        ) {

            throw new RowImportFailedException(
                "Nomor berkas '{$this->data['nomor_berkas']}' sudah digunakan."
            );
        }
    }

    public function resolveRecord(): Pegawai
    {
        return Pegawai::firstOrNew([
            'nip' => $this->data['nip'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import pegawai selesai. ' .
            Number::format($import->successful_rows) . ' ' .
            str('baris')->plural($import->successful_rows) .
            ' berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' .
                Number::format($failedRowsCount) .
                ' baris gagal diimport.';
        }

        return $body;
    }
}
