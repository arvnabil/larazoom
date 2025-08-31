<?php

namespace App\Filament\Resources\ZoomOauthTokens\Pages;

use App\Filament\Resources\ZoomOauthTokens\ZoomOauthTokenResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditZoomOauthToken extends EditRecord
{
    protected static string $resource = ZoomOauthTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
