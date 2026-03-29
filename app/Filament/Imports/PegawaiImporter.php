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
                ->requiredMapping(),

            ImportColumn::make('nama')
                ->requiredMapping(),

            ImportColumn::make('thn_angkat')
                ->numeric(),

            ImportColumn::make('tgl_lahir'),

            ImportColumn::make('jenis_kelamin'),

            ImportColumn::make('unit_pegawai'),

            ImportColumn::make('sts_pegawai'),

            ImportColumn::make('nomor_berkas')
                ->requiredMapping(),

            ImportColumn::make('lemari')
                ->numeric(),

            ImportColumn::make('hambalan')
                ->numeric(),

            ImportColumn::make('jenis_pegawai')
                ->requiredMapping(),

            ImportColumn::make('TMT'),

            ImportColumn::make('tgl_pensiun'),

            ImportColumn::make('masa_kerja')
                ->numeric(),

            ImportColumn::make('tgl_mutasi_masuk'),
            ImportColumn::make('tgl_mutasi_keluar'),
        ];
    }


    private function isValidDate($date): bool
    {
        $date = trim($date);

        // Cek format dengan regex
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return false;
        }

        // Cek apakah tanggal valid
        $parts = explode('-', $date);
        return checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0]);
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

        if (empty($this->data['TMT'])) {
            throw new RowImportFailedException('TMT pegawai wajib diisi.');
        }

        if (empty($this->data['thn_angkat'])) {
            throw new RowImportFailedException('Tahun Angkat pegawai wajib diisi.');
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

        // --- Validasi tgl_lahir (wajib diisi) ---
        if (empty($this->data['tgl_lahir'])) {
            throw new RowImportFailedException('Tanggal lahir wajib diisi.');
        }

        if (!strtotime($this->data['tgl_mutasi_masuk'])) {
            throw new RowImportFailedException(
                "Format tanggal mutasi masuk tidak valid: '{$this->data['tgl_lahir']}'. Gunakan format YYYY-MM-DD."
            );
        }

        if (!strtotime($this->data['tgl_mutasi_keluar'])) {
            throw new RowImportFailedException(
                "Format tanggal mutasi keluar tidak valid: '{$this->data['tgl_lahir']}'. Gunakan format YYYY-MM-DD."
            );
        }

        if (!strtotime($this->data['tgl_lahir'])) {
            throw new RowImportFailedException(
                "Format tanggal lahir tidak valid: '{$this->data['tgl_lahir']}'. Gunakan format YYYY-MM-DD."
            );
        }

        // --- Validasi TMT (wajib diisi) ---
        if (!empty($this->data['TMT'])) {
            if (!$this->isValidDate($this->data['TMT'])) {
                throw new RowImportFailedException(
                    "Format tanggal TMT  tidak valid. Gunakan format: YYYY-MM-DD (contoh: 2025-08-01)."
                );
            }
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
