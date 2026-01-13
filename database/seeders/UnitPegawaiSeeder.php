<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'IPB',
            'Biro Komunikasi',
            'Biro Legislasi Dan Pelayanan Hukum',
            'Biro Umum dan Instalasi',
            'Direktorat Administrasi Pendidikan Dan Penerimaan Mahasiswa Baru',
            'Direktorat Bisnis Dan Manajemen Aset Komersial',
            'Direktorat Inovasi Dan Kewirausahaan',
            'Direktorat Kemahasiswaan Dan Pengembangan Karir',
            'Direktorat Kerjasama Dan Hubungan Alumni',
            'Direktorat Keuangan Dan Akuntansi',
            'Direktorat Pengembangan Program Dan Teknologi Pendidikan',
            'Direktorat Perencanaan, Pemonitoran, Dan Evaluasi',
            'Direktorat Program Internasional',
            'Direktorat Sistem Informasi Dan Transformasi Digital',
            'Direktorat Sumberdaya Manusia',
            'Kantor Manajemen Mutu Dan Audit Internal',
            'Lembaga Pengembangan Institut',
            'Lembaga Penelitian & Pengabdian Kepada Masyarakat',
            'Fakultas Pertanian',
            'Sekolah Kedokteran Hewan dan Biomedis',
            'Fakultas Perikanan Dan Ilmu Kelautan',
            'Fakultas Peternakan',
            'Fakultas Kehutanan dan Lingkungan',
            'Fakultas Teknologi Pertanian',
            'Fakultas Matematika Dan Ilmu Pengetahuan Alam',
            'Fakultas Ekonomi dan Manajemen',
            'Fakultas Ekologi Manusia',
            'Fakultas Kedokteran',
            'Sekolah Pascasarjana',
            'Sekolah Bisnis',
            'Sekolah Vokasi',
            'Program Pendidikan Kompetensi Umum',
            'Program Pendidikan Di Luar Kampus Utama',
            'Unit Laboratorium Terpadu',
            'Perpustakaan',
            'Unit Laboratorium Riset Unggulan',
            'Unit Arsip',
            'Unit Keamanan Kampus',
            'Unit Kesehatan',
            'Unit Layanan Pengadaan',
            'Unit Olah Raga Dan Seni',
            'Unit Pelatihan Bahasa',
            'Unit Science And Techno Park',
            'Unit Transportasi Kampus',
            'Green Tv',
        ];

        foreach ($units as $unit) {
            DB::table('tb_unit_pegawai')->updateOrInsert(
                ['name' => $unit],
                ['name' => $unit]
            );
        }
    }
}
