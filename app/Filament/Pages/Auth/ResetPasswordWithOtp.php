<?php

namespace App\Filament\Pages\Auth;

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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Locked;

/**
 * Step 3 dari alur lupa password: user memasukkan password baru.
 * Halaman ini didaftarkan lewat slot resetAction di $panel->passwordReset(),
 * jadi otomatis kebagian middleware "signed" bawaan Filament untuk rute ini
 * -> URL-nya WAJIB dibuat lewat static::getUrl() (menghasilkan signed URL),
 * jangan pernah di-construct manual.
 *
 * @property-read Schema $form
 */
class ResetPasswordWithOtp extends SimplePage
{
    use WithRateLimiting;

    public ?array $data = [];

    #[Locked]
    public ?string $email = null;

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null): string
    {
        $panelInstance = filled($panel) ? Filament::getPanel($panel) : Filament::getCurrentOrDefaultPanel();

        return URL::signedRoute(
            $panelInstance->generateRouteName('auth.password-reset.reset'),
            $parameters,
            absolute: $isAbsolute,
        );
    }

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $email = request()->query('email');

        if (! $this->hasValidOtpSession($email)) {
            Notification::make()
                ->title('Sesi verifikasi tidak valid atau sudah kedaluwarsa')
                ->body('Silakan ulangi proses lupa password dari awal.')
                ->danger()
                ->send();

            $this->redirect(RequestOtp::getUrl());

            return;
        }

        $this->email = $email;

        $this->form->fill();
    }

    protected function hasValidOtpSession(?string $email): bool
    {
        if (blank($email)) {
            return false;
        }

        if (session('password_reset_otp_email') !== $email) {
            return false;
        }

        if (! session('password_reset_otp_verified')) {
            return false;
        }

        $otp = PasswordResetOtp::where('email', $email)->latest('id')->first();

        return $otp && $otp->isVerificationStillValid();
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('password')
                ->label('Password Baru')
                ->password()
                ->revealable()
                ->required()
                ->rule(PasswordRule::default())
                ->same('passwordConfirmation')
                ->autocomplete('new-password')
                ->autofocus(),
            TextInput::make('passwordConfirmation')
                ->label('Konfirmasi Password Baru')
                ->password()
                ->revealable()
                ->required()
                ->dehydrated(false)
                ->autocomplete('new-password'),
        ]);
    }

    public function resetPassword(): void
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title("Terlalu banyak percobaan. Coba lagi dalam {$exception->secondsUntilAvailable} detik.")
                ->danger()
                ->send();

            return;
        }

        if (! $this->hasValidOtpSession($this->email)) {
            Notification::make()
                ->title('Sesi verifikasi tidak valid atau sudah kedaluwarsa')
                ->body('Silakan ulangi proses lupa password dari awal.')
                ->danger()
                ->send();

            $this->redirect(RequestOtp::getUrl());

            return;
        }

        $data = $this->form->getState();

        $user = User::where('email', $this->email)->first();

        if (! $user) {
            Notification::make()
                ->title('Akun tidak ditemukan')
                ->danger()
                ->send();

            return;
        }

        $user->forceFill([
            'password' => Hash::make($data['password']),
            'remember_token' => \Illuminate\Support\Str::random(60),
        ])->save();

        // Bersihkan OTP & sesi supaya tidak bisa dipakai ulang.
        PasswordResetOtp::where('email', $this->email)->delete();
        session()->forget(['password_reset_otp_email', 'password_reset_otp_verified']);

        Notification::make()
            ->title('Password berhasil diubah')
            ->body('Silakan login dengan password baru Anda.')
            ->success()
            ->send();

        $this->redirect(filament()->getLoginUrl());
    }

    public function getTitle(): string|Htmlable
    {
        return 'Buat Password Baru';
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Buat Password Baru';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('resetPassword')
                ->label('Simpan Password Baru')
                ->submit('resetPassword'),
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
            ->livewireSubmitHandler('resetPassword')
            ->footer([
                Actions::make($this->getFormActions())
                    ->fullWidth($this->hasFullWidthFormActions())
                    ->key('form-actions'),
            ]);
    }
}
