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
        Schema::create('berkas_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nip')
                ->nullable();
            $table->foreign('nip')
                ->references('nip')
                ->on('pegawai')
                ->cascadeOnDelete();
            $table->string('nomor_berkas')->index();
            $table->string('perihal');
            $table->date('tgl_berkas');
            $table->string('asal_berkas');
            $table->date('tgl_agenda');
            $table->integer('tahun');
            $table->string('nama_penyimpan');
            $table->string('locate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_masuk');
    }
};
