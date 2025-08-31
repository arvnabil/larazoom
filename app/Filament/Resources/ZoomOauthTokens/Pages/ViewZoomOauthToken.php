<?php

namespace App\Filament\Resources\ZoomOauthTokens\Pages;

use App\Filament\Resources\ZoomOauthTokens\ZoomOauthTokenResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewZoomOauthToken extends ViewRecord
{
    protected static string $resource = ZoomOauthTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
