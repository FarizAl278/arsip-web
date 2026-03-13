<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_berkas');
            $table->string('status_berkas');
            $table->string('sifat_layanan')->nullable();
            $table->string('sifat_lain')->default('tidak');
            $table->string('berkas_layanan')->nullable();
            $table->string('berkas_lain')->default('tidak');
            $table->string('nama');
            $table->string('subdit');
            $table->string('unit_kerja');
            $table->string('seksi');
            $table->string('operator');
            $table->date('tanggal');
            $table->string('keluar')->default('PERSONAL');
            $table->date('kembali')->nullable();
            $table->enum('internal', ['Ya', 'Tidak'])->default('Ya');
            $table->string('jenis_pegawai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
