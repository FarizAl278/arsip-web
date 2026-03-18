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
                ->rules(['required', 'max:255'])
                ->validationMessages([
                    'required' => 'Kolom NIP wajib diisi.',
                    'max'      => 'NIP maksimal 255 karakter.',
                ]),

            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->validationMessages([
                    'required' => 'Kolom Nama wajib diisi.',
                    'max'      => 'Nama maksimal 255 karakter.',
                ]),

            ImportColumn::make('thn_angkat')
                ->numeric(),

            ImportColumn::make('tgl_lahir')
                ->rules(['max:255'])
                ->validationMessages([
                    'max' => 'Tanggal lahir maksimal 255 karakter.',
                ]),

            ImportColumn::make('jenis_kelamin'),

            ImportColumn::make('unit_pegawai')
                ->rules(['max:255'])
                ->validationMessages([
                    'max' => 'Unit pegawai maksimal 255 karakter.',
                ]),

            ImportColumn::make('sts_pegawai'),

            ImportColumn::make('nomor_berkas')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->validationMessages([
                    'required' => 'Kolom Nomor Berkas wajib diisi.',
                    'max'      => 'Nomor berkas maksimal 255 karakter.',
                ]),

            ImportColumn::make('lemari')
                ->numeric(),

            ImportColumn::make('hambalan')
                ->numeric(),

            ImportColumn::make('jenis_pegawai')
                ->requiredMapping()
                ->rules(['required'])
                ->validationMessages([
                    'required' => 'Kolom Jenis Pegawai wajib diisi.',
                ]),

            ImportColumn::make('TMT'),

            ImportColumn::make('tgl_pensiun'),

            ImportColumn::make('masa_kerja')
                ->numeric(),
        ];
    }

    /**
     * Error handling per row — fallback layer di atas validasi rules.
     */
    protected function beforeSave(): void
    {
        // --- Wajib isi ---
        if (empty($this->data['nip'])) {
            throw new RowImportFailedException('NIP wajib diisi.');
        }

        if (empty($this->data['nama'])) {
            throw new RowImportFailedException('Nama wajib diisi.');
        }

        if (empty($this->data['nomor_berkas'])) {
            throw new RowImportFailedException('Nomor berkas wajib diisi.');
        }

        if (empty($this->data['jenis_pegawai'])) {
            throw new RowImportFailedException('Jenis pegawai wajib diisi.');
        }

        // --- Validasi jenis kelamin (hanya jika diisi) ---
        if (!empty($this->data['jenis_kelamin'])) {
            if (!in_array($this->data['jenis_kelamin'], ['Laki-laki', 'Perempuan'])) {
                throw new RowImportFailedException(
                    "Jenis kelamin harus 'Laki-laki' atau 'Perempuan', nilai yang diterima: '{$this->data['jenis_kelamin']}'."
                );
            }
        }

        // --- Validasi status pegawai (hanya jika diisi) ---
        if (!empty($this->data['sts_pegawai'])) {
            if (!in_array($this->data['sts_pegawai'], ['tendik', 'dosen'])) {
                throw new RowImportFailedException(
                    "Status pegawai hanya boleh 'tendik' atau 'dosen', nilai yang diterima: '{$this->data['sts_pegawai']}'."
                );
            }
        }

        // --- Validasi jenis pegawai ---
        $validJenisPegawai = ['Pns', 'Non Pns Tetap', 'Non Pns Kontrak', 'Pensiun'];
        if (!in_array($this->data['jenis_pegawai'], $validJenisPegawai)) {
            $valid = implode(', ', $validJenisPegawai);
            throw new RowImportFailedException(
                "Jenis pegawai '{$this->data['jenis_pegawai']}' tidak valid. Pilihan yang tersedia: {$valid}."
            );
        }

        // --- Validasi nomor berkas unik (skip jika update record yang sama) ---
        if (Pegawai::where('nomor_berkas', $this->data['nomor_berkas'])
            ->where('nip', '!=', $this->data['nip'])
            ->exists()
        ) {
            throw new RowImportFailedException(
                "Nomor berkas '{$this->data['nomor_berkas']}' sudah digunakan oleh NIP lain."
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