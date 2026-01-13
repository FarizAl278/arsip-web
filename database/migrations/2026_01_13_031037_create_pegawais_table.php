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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->integer('thn_angkat');
            $table->date('tgl_lahir');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->foreignId('unit_pegawai_id')
                ->constrained('tb_unit_pegawai')
                ->cascadeOnDelete();
            $table->enum('sts_pegawai', ['tendik', 'dosen']);
            $table->string('nomor_berkas')->index('index_nomor_berkas');
            $table->integer('lemari');
            $table->integer('hambalan');
            $table->bigInteger('masa_kerja');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
