<?php

namespace App\Filament\Pages\Auth;

use App\Mail\OtpPasswordResetMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Locked;

/**
 * Step 2 dari alur lupa password: user memasukkan kode OTP 6 digit
 * yang dikirim ke emailnya. Halaman ini didaftarkan secara manual lewat
 * $panel->routes() di AdminPanelProvider (bukan lewat slot passwordReset()
 * bawaan Filament, karena bawaan cuma sediakan 2 slot: request & reset).
 *
 * @property-read Schema $form
 */
class VerifyOtp extends SimplePage
{
    use WithRateLimiting;

    public ?array $data = [];

    #[Locked]
    public ?string $email = null;

    /**
     * Nama route yang didaftarkan manual di AdminPanelProvider::panel().
     */
    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null): string
    {
        return Filament::getCurrentOrDefaultPanel()->route('verify-otp', $parameters, $isAbsolute);
    }

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->email = session('password_reset_otp_email');

        // Kalau user buka halaman ini tanpa melalui step 1 (misal langsung akses URL),
        // lempar balik ke halaman request OTP.
        if (blank($this->email)) {
            $this->redirect(RequestOtp::getUrl());

            return;
        }

        $this->form->fill();
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('otp_code')
                ->label('Kode OTP')
                ->numeric()
                ->length(6)
                ->required()
                ->autofocus()
                ->extraInputAttributes(['inputmode' => 'numeric', 'autocomplete' => 'one-time-code']),
        ]);
    }

    public function verify(): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title("Terlalu banyak percobaan. Coba lagi dalam {$exception->secondsUntilAvailable} detik.")
                ->danger()
                ->send();

            return;
        }

        $data = $this->form->getState();

        $otp = PasswordResetOtp::where('email', $this->email)->latest('id')->first();

        if (! $otp || $otp->isExpired() || $otp->hasReachedMaxAttempts()) {
            Notification::make()
                ->title('Kode OTP tidak valid atau sudah kedaluwarsa')
                ->body('Silakan minta kode OTP baru.')
                ->danger()
                ->send();

            return;
        }

        if (! $otp->checkCode($data['otp_code'])) {
            $otp->increment('attempts');

            Notification::make()
                ->title('Kode OTP salah')
                ->danger()
                ->send();

            return;
        }

        $otp->update(['verified_at' => now()]);

        session(['password_reset_otp_verified' => true]);

        Notification::make()
            ->title('Kode OTP berhasil diverifikasi')
            ->success()
            ->send();

        $this->redirect(ResetPasswordWithOtp::getUrl(['email' => $this->email]));
    }

    public function resend(): void
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title("Tunggu {$exception->secondsUntilAvailable} detik sebelum kirim ulang.")
                ->danger()
                ->send();

            return;
        }

        $user = User::where('email', $this->email)->first();

        if ($user) {
            [$otp, $plainOtp] = PasswordResetOtp::generateFor($this->email);

            Mail::to($this->email)->send(new OtpPasswordResetMail($plainOtp));
        }

        Notification::make()
            ->title('Kode OTP baru telah dikirim')
            ->success()
            ->send();
    }

    public function getTitle(): string|Htmlable
    {
        return 'Verifikasi Kode OTP';
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Verifikasi Kode OTP';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return "Kode OTP telah dikirim ke {$this->email}";
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('verify')
                ->label('Verifikasi')
                ->submit('verify'),
            Action::make('resend')
                ->label('Kirim Ulang Kode OTP')
                ->link()
                ->action('resend'),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            $this->getFormContentComponent(),
        ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('verify')
            ->footer([
                Actions::make($this->getFormActions())
                    ->fullWidth($this->hasFullWidthFormActions())
                    ->key('form-actions'),
            ]);
    }
}
