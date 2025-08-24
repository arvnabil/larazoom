<?php

namespace App\Filament\Resources\Subjects\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('teacher_id')
                    ->label('Teacher')
                    ->options(User::where('role', 'teacher')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }
}
