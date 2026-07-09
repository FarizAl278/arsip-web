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
        Schema::create('password_reset_otps', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('otp_code'); // disimpan dalam bentuk hash (Hash::make), bukan plain text
            $table->unsignedTinyInteger('attempts')->default(0); // percobaan verifikasi OTP yang gagal
            $table->timestamp('expires_at'); // OTP kedaluwarsa 5 menit setelah dibuat
            $table->timestamp('verified_at')->nullable(); // diisi saat OTP berhasil diverifikasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_otps');
    }
};
