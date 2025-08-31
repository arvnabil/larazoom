<?php

namespace App\Filament\Resources\ZoomHosts\Pages;

use App\Filament\Resources\ZoomHosts\ZoomHostResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditZoomHost extends EditRecord
{
    protected static string $resource = ZoomHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
