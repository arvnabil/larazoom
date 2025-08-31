<?php

namespace App\Filament\Resources\ZoomOauthTokens\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ZoomOauthTokenInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('expires_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
