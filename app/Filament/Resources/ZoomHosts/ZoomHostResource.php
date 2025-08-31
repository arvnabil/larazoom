<?php

namespace App\Filament\Resources\ZoomHosts;

use App\Filament\Resources\ZoomHosts\Pages\CreateZoomHost;
use App\Filament\Resources\ZoomHosts\Pages\EditZoomHost;
use App\Filament\Resources\ZoomHosts\Pages\ListZoomHosts;
use App\Filament\Resources\ZoomHosts\Pages\ViewZoomHost;
use App\Filament\Resources\ZoomHosts\Schemas\ZoomHostForm;
use App\Filament\Resources\ZoomHosts\Schemas\ZoomHostInfolist;
use App\Filament\Resources\ZoomHosts\Tables\ZoomHostsTable;
use App\Models\ZoomHost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ZoomHostResource extends Resource
{
    protected static ?string $model = ZoomHost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Zoom Host';

    protected static ?string $modelLabel = 'Zoom Host';

    protected static string | UnitEnum | null $navigationGroup = 'Tools';

    public static function form(Schema $schema): Schema
    {
        return ZoomHostForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ZoomHostInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ZoomHostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListZoomHosts::route('/'),
            'create' => CreateZoomHost::route('/create'),
            'view' => ViewZoomHost::route('/{record}'),
            'edit' => EditZoomHost::route('/{record}/edit'),
        ];
    }
}
