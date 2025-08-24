<?php

namespace App\Filament\Resources\Topics\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class TopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        Select::make('chapter_id')
                            ->label('Bab')
                            ->relationship('chapter', 'title')
                            ->searchable()
                            ->required(),
                        TextInput::make('title')
                            ->label('Judul Topik')
                            ->required(),
                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()->default(0)->required(),
                    ])->columns(2),

                Section::make('Konten Materi')
                ->schema([
                    // --- KOMPONEN PEMICU ---
                    Select::make('content_type')
                        ->options([
                            'text' => 'Teks / Artikel',
                            'file' => 'File / Dokumen',
                            'meeting' => 'Live Meeting (Zoom)',
                        ])
                        ->required()
                        ->label('Tipe Konten')
                        ->live(), // Ini adalah "saklar" utama untuk reaktivitas.

                    // --- KOMPONEN KONDISIONAL #1: Teks ---
                    RichEditor::make('content')
                        ->label('Isi Artikel')
                        // Hanya tampil jika 'content_type' adalah 'text'.
                        ->visible(fn (Get $get): bool => $get('content_type') === 'text')
                        // Hanya wajib diisi jika 'content_type' adalah 'text'.
                        ->requiredIf('content_type', 'text'),

                    // --- KOMPONEN KONDISIONAL #2: File ---
                    FileUpload::make('file_path')
                        ->label('Unggah File')
                        ->directory('topic-files')
                        // Hanya tampil jika 'content_type' adalah 'file'.
                        ->visible(fn (Get $get): bool => $get('content_type') === 'file')
                        // Hanya wajib diisi jika 'content_type' adalah 'file'.
                        ->requiredIf('content_type', 'file'),

                    // --- KOMPONEN KONDISIONAL #3: Meeting ---
                    Group::make()
                        // Seluruh grup ini hanya tampil jika 'content_type' adalah 'meeting'.
                        ->visible(fn (Get $get): bool => $get('content_type') === 'meeting')
                        ->schema([
                            DateTimePicker::make('start_time')
                                ->label('Waktu Mulai Meeting')
                                ->native(false) // Menggunakan UI date picker canggih.
                                ->displayFormat('d M Y H:i') // Format tampilan tanggal.
                                ->seconds(false) // Sembunyikan input detik.
                                ->requiredIf('content_type', 'meeting'),

                            TextInput::make('duration')
                                ->label('Durasi (menit)')
                                ->numeric()
                                ->minValue(1)
                                ->requiredIf('content_type', 'meeting'),
                        ]),
                ]),
            ]);
    }
}
