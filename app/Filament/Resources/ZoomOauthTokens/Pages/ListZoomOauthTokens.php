<?php

namespace App\Filament\Resources\ZoomOauthTokens\Pages;

use App\Filament\Resources\ZoomOauthTokens\ZoomOauthTokenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListZoomOauthTokens extends ListRecords
{
    protected static string $resource = ZoomOauthTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
