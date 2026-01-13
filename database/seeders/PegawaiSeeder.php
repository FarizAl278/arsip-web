<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // ambil semua id unit pegawai
        $unitIds = DB::table('tb_unit_pegawai')->pluck('id')->toArray();

        $data = [];

        for ($i = 1; $i <= 50; $i++) {
            $tglLahir = Carbon::now()->subYears(rand(25, 58))->subDays(rand(0, 365));
            $thnAngkat = rand(2000, 2022);

            $data[] = [
                'nip' => '1987' . str_pad($i, 10, '0', STR_PAD_LEFT),
                'nama' => 'Pegawai ' . $i,
                'thn_angkat' => $thnAngkat,
                'tgl_lahir' => $tglLahir->toDateString(),
                'jenis_kelamin' => rand(0, 1) ? 'laki-laki' : 'perempuan',
                'unit_pegawai_id' => $unitIds[array_rand($unitIds)],
                'sts_pegawai' => rand(0, 1) ? 'tendik' : 'dosen',
                'nomor_berkas' => 'BRK-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'lemari' => rand(1, 10),
                'hambalan' => rand(1, 5),
                'jenis_pegawai' => collect([
                    'Pns',
                    'Non Pns Tetap',
                    'Non Pns Kontrak',
                    'Pensiun'
                ])->random(),
                'masa_kerja' => now()->year - $thnAngkat,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('pegawai')->insert($data);
    }
}
