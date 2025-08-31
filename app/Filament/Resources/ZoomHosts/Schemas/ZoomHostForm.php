<?php

namespace App\Filament\Resources\ZoomHosts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ZoomHostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('zoom_user_id')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
