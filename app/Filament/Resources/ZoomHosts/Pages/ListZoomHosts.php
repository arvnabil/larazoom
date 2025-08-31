<?php

namespace App\Filament\Resources\ZoomHosts\Pages;

use App\Filament\Resources\ZoomHosts\ZoomHostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListZoomHosts extends ListRecords
{
    protected static string $resource = ZoomHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
