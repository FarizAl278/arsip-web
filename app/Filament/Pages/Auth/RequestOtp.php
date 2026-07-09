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

/**
 * Step 1 dari alur lupa password: user memasukkan email, lalu sistem
 * mengirimkan kode OTP 6 digit ke email tersebut (berlaku 5 menit).
 *
 * @property-read Schema $form
 */
class RequestOtp extends SimplePage
{
    use WithRateLimiting;

    public ?array $data = [];

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
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
            $this->getEmailFormComponent(),
        ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Alamat email')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    public function request(): void
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

        $data = $this->form->getState();
        $email = $data['email'];

        // Selalu tampilkan pesan sukses yang sama baik email terdaftar atau
        // tidak, supaya orang tidak bisa menebak-nebak email mana yang punya akun.
        $user = User::where('email', $email)->first();

        if ($user) {
            [$otp, $plainOtp] = PasswordResetOtp::generateFor($email);

            Mail::to($email)->send(new OtpPasswordResetMail($plainOtp));
        }

        session(['password_reset_otp_email' => $email]);

        Notification::make()
            ->title('Kode OTP telah dikirim')
            ->body('Jika email terdaftar, kode OTP telah dikirim ke email tersebut. Silakan cek kotak masuk (dan folder spam).')
            ->success()
            ->send();

        $this->redirect(VerifyOtp::getUrl());
    }

    public function getTitle(): string|Htmlable
    {
        return 'Lupa Password';
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Lupa Password';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return $this->loginAction;
    }

    public function loginAction(): Action
    {
        return Action::make('login')
            ->link()
            ->label('Kembali ke halaman login')
            ->url(filament()->getLoginUrl());
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('request')
                ->label('Kirim Kode OTP')
                ->submit('request'),
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
            ->livewireSubmitHandler('request')
            ->footer([
                Actions::make($this->getFormActions())
                    ->fullWidth($this->hasFullWidthFormActions())
                    ->key('form-actions'),
            ]);
    }
}
