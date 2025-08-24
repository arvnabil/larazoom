<?php

namespace App\Filament\Resources\Meetings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Services\ZoomService; // Service class buatan kita
use App\Models\ZoomHost;
use Carbon\Carbon;
class MeetingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('topic_id')
                    ->required()
                    ->numeric(),
                TextInput::make('zoom_host_id')
                    ->required()
                    ->numeric(),
                TextInput::make('topic')
                    ->required(),
                DateTimePicker::make('start_time')
                    ->required(),
                TextInput::make('duration')
                    ->required()
                    ->numeric(),
                TextInput::make('zoom_meeting_id')
                    ->required()
                    ->numeric(),
                Textarea::make('zoom_start_url')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('zoom_join_url')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
