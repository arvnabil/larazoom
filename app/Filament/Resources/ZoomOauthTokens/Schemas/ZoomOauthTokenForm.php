<?php

namespace App\Filament\Resources\ZoomOauthTokens\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ZoomOauthTokenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            ]);
    }
}
