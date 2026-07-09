<?php

namespace App\Mail;

use App\Models\PasswordResetOtp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,
    ) {}

    public function build(): static
    {
        return $this
            ->subject('Kode OTP Reset Password - ' . config('app.name'))
            ->view('emails.otp-password-reset')
            ->with([
                'otpCode' => $this->otpCode,
                'expiryMinutes' => PasswordResetOtp::OTP_LIFETIME_MINUTES,
            ]);
    }
}
