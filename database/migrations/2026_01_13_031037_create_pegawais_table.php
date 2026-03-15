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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->index();
            $table->string('nama');
            $table->integer('thn_angkat')->nullable();
            $table->string('tgl_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('unit_pegawai')->nullable();
            $table->enum('sts_pegawai', ['tendik', 'dosen'])->nullable();
            $table->string('nomor_berkas')->index('index_nomor_berkas');
            $table->integer('lemari')->nullable();
            $table->integer('hambalan')->nullable();
            $table->enum('jenis_pegawai', ['Pns', 'Non Pns Tetap', 'Non Pns Kontrak', 'Pensiun']);
            $table->string('TMT')->nullable();
            $table->string('tgl_pensiun')->nullable();
            $table->bigInteger('masa_kerja')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
