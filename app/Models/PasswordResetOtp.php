<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PasswordResetOtp extends Model
{
    /**
     * Berapa lama (menit) satu kode OTP berlaku sejak dibuat.
     */
    public const OTP_LIFETIME_MINUTES = 5;

    /**
     * Berapa lama (menit) sesi "sudah verifikasi OTP" berlaku sebelum
     * user wajib mengulang dari awal (mencegah token verified menggantung selamanya).
     */
    public const VERIFIED_LIFETIME_MINUTES = 10;

    /**
     * Maksimal percobaan salah memasukkan kode OTP sebelum kode dianggap hangus.
     */
    public const MAX_ATTEMPTS = 5;

    protected $fillable = [
        'email',
        'otp_code',
        'attempts',
        'expires_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Buat kode OTP baru untuk sebuah email. Otomatis menghapus kode lama
     * milik email yang sama supaya hanya ada 1 kode aktif dalam satu waktu.
     *
     * @return array{0: self, 1: string} [model, kode OTP plain 6 digit]
     */
    public static function generateFor(string $email): array
    {
        static::where('email', $email)->delete();

        $plainOtp = (string) random_int(100000, 999999);

        $otp = static::create([
            'email' => $email,
            'otp_code' => Hash::make($plainOtp),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(self::OTP_LIFETIME_MINUTES),
            'verified_at' => null,
        ]);

        return [$otp, $plainOtp];
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Apakah status "terverifikasi" masih berlaku untuk lanjut ke step ganti password.
     */
    public function isVerificationStillValid(): bool
    {
        return $this->isVerified()
            && now()->lessThan($this->verified_at->addMinutes(self::VERIFIED_LIFETIME_MINUTES));
    }

    public function hasReachedMaxAttempts(): bool
    {
        return $this->attempts >= self::MAX_ATTEMPTS;
    }

    public function checkCode(string $code): bool
    {
        return Hash::check($code, $this->otp_code);
    }
}
