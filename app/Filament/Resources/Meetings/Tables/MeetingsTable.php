<?php

namespace App\Filament\Resources\Meetings\Tables;

use App\Models\Meeting;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MeetingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('chapter_title')
                    ->label('Bab')
                    ->getStateUsing(function (Meeting $record): ?string {
                        return $record->topic()->first()?->chapter?->title;
                    }),
                TextColumn::make('topic')
                    ->label('Judul Meeting')
                    ->searchable(),
                TextColumn::make('zoomHost.name')
                    ->label('Host')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('duration')
                    ->label('Durasi (menit)')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('zoom_meeting_id')
                    ->label('Meeting ID')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('join_app')
                        ->label('Join via Aplikasi (Native)')
                        ->url(fn (Meeting $record): string => $record->zoom_join_url, shouldOpenInNewTab: true)
                        ->icon('heroicon-o-computer-desktop'),
                    Action::make('join_component')
                        ->label('Join via Web (Embedded)')
                        ->url(fn (Meeting $record): string => route('meetings.join', $record), shouldOpenInNewTab: true)
                        ->icon('heroicon-o-window'),
                    Action::make('join_full_web')
                        ->label('Join via Web (Full Page)')
                        ->url(fn (Meeting $record): string => route('meetings.join', ['meeting' => $record, 'view' => 'full']), shouldOpenInNewTab: true)
                        ->icon('heroicon-o-globe-alt'),
                ])
                ->label('Join Meeting')
                ->icon('heroicon-o-video-camera')
                ->color('success'),
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
