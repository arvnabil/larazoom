<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(User::class, 'email', ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    // Hash password saat menyimpan
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    // Jangan mengisi ulang field password saat edit
                    ->dehydrated(fn ($state) => filled($state))
                    // Wajib diisi hanya saat membuat user baru
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload() // Memuat semua role saat halaman dibuka
                    ->searchable(),
            ]);
    }
}
