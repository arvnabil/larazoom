<?php

namespace App\Filament\Resources\ZoomOauthTokens;

use App\Filament\Resources\ZoomOauthTokens\Pages\CreateZoomOauthToken;
use App\Filament\Resources\ZoomOauthTokens\Pages\EditZoomOauthToken;
use App\Filament\Resources\ZoomOauthTokens\Pages\ListZoomOauthTokens;
use App\Filament\Resources\ZoomOauthTokens\Pages\ViewZoomOauthToken;
use App\Filament\Resources\ZoomOauthTokens\Schemas\ZoomOauthTokenForm;
use App\Filament\Resources\ZoomOauthTokens\Schemas\ZoomOauthTokenInfolist;
use App\Filament\Resources\ZoomOauthTokens\Tables\ZoomOauthTokensTable;
use App\Models\ZoomOauthToken;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ZoomOauthTokenResource extends Resource
{
    protected static ?string $model = ZoomOauthToken::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Zoom Oauth Token';

    protected static ?string $modelLabel = 'Zoom Oauth Token';

    protected static string | UnitEnum | null $navigationGroup = 'Tools';

    public static function form(Schema $schema): Schema
    {
        return ZoomOauthTokenForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ZoomOauthTokenInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ZoomOauthTokensTable::configure($table);
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
            'index' => ListZoomOauthTokens::route('/'),
            'create' => CreateZoomOauthToken::route('/create'),
            'view' => ViewZoomOauthToken::route('/{record}'),
            'edit' => EditZoomOauthToken::route('/{record}/edit'),
        ];
    }
}
