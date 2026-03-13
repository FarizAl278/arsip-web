<?php

namespace App\Filament\Services;

use App\Models\Layanan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class LayananService
{
    public static function pinjamBerkas(Collection $records, array $data)
    {
        $record = $records->first();

        $isDipinjam = Layanan::where('kode_berkas', $record->nomor_berkas)
            ->whereNull('kembali')
            ->exists();

        if ($isDipinjam) {
            Notification::make()
                ->title('Peminjaman Gagal')
                ->body("Berkas pegawai <strong>{$record->nip}</strong> masih dipinjam dan belum dikembalikan.")
                ->danger()
                ->send();

            return;
        }

        Layanan::create([
            'kode_berkas'    => $record->nomor_berkas,
            'status_berkas'  => $data['status_berkas'],
            'sifat_layanan'  => $data['status_berkas'],
            'sifat_lain'     => $data['sifat_lain'] ?? 'tidak',
            'berkas_layanan' => $data['berkas_layanan'],
            'berkas_lain'    => $data['berkas_lain'] ?? 'tidak',
            'nama'           => $data['nama'],
            'subdit'         => $data['subdit'],
            'unit_kerja'     => $data['unit_kerja'],
            'seksi'          => $data['seksi'],
            'operator'       => Auth::user()->name,
            'tanggal'        => now(),
            'kembali'        => null,
            'internal'       => $data['internal'],
            'jenis_pegawai'  => $record->jenis_pegawai,
        ]);

        $layanan = $data['berkas_layanan'] === 'Berkas Tertentu'
            ? $data['berkas_lain']
            : $data['berkas_layanan'];

        Notification::make()
            ->title('Peminjaman Berhasil')
            ->body("Berkas pegawai <strong>{$record->nomor_berkas}</strong> dipinjam <strong>{$layanan}</strong>.")
            ->success()
            ->send();
    }
}
