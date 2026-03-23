<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255)
                    ->autocomplete('name'),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->autocomplete('email'),


                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->minLength(8)
                    ->maxLength(255)
                    ->revealable()
                    ->autocomplete('new-password')
                    ->confirmed()
                    ->helperText('Minimum 8 characters'),

                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrated(false)
                    ->revealable()
                    ->autocomplete('new-password'),

                Toggle::make('is_admin')
                    ->label('Admin')
                    ->helperText('Nonaktifkan jika user bukan admin'),
            ]);
    }
}
