<?php

namespace App\Filament\Resources\ZoomHosts\Pages;

use App\Filament\Resources\ZoomHosts\ZoomHostResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewZoomHost extends ViewRecord
{
    protected static string $resource = ZoomHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
