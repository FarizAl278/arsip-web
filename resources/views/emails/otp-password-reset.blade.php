<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kode OTP Reset Password</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7; padding: 32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden;">
                    <tr>
                        <td style="background-color:#1d4ed8; padding:20px 32px;">
                            <span style="color:#ffffff; font-size:18px; font-weight:bold;">{{ config('app.name') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="font-size:15px; color:#111827; margin:0 0 16px;">Halo,</p>
                            <p style="font-size:15px; color:#111827; margin:0 0 24px;">
                                Kami menerima permintaan untuk mereset password akun Anda. Gunakan kode OTP di bawah ini untuk melanjutkan proses reset password.
                            </p>
                            <div style="text-align:center; margin:32px 0;">
                                <span style="display:inline-block; font-size:32px; letter-spacing:8px; font-weight:bold; color:#1d4ed8; background-color:#eff6ff; padding:16px 24px; border-radius:8px;">
                                    {{ $otpCode }}
                                </span>
                            </div>
                            <p style="font-size:14px; color:#4b5563; margin:0 0 8px;">
                                Kode ini berlaku selama <strong>{{ $expiryMinutes }} menit</strong> sejak email ini dikirim.
                            </p>
                            <p style="font-size:14px; color:#4b5563; margin:0;">
                                Jika Anda tidak merasa meminta reset password, abaikan email ini dan jangan bagikan kode ini kepada siapa pun.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px; background-color:#f9fafb;">
                            <p style="font-size:12px; color:#9ca3af; margin:0;">
                                Email ini dikirim otomatis, mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
