<?php

namespace App\Filament\Resources\Chapters\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ChapterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
