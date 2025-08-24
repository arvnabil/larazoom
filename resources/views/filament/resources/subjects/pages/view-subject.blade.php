<x-filament-panels::page>
    @php
        $subject = $this->getRecord();
    @endphp

    <x-filament::section>
        <x-slot name="heading">
            {{ $subject->name }}
        </x-slot>
        <x-slot name="description">
            Diajar oleh: {{ $subject->teacher->name }}
        </x-slot>

        <div class="prose dark:prose-invert max-w-none">
            {!! $subject->description !!}
        </div>
    </x-filament::section>

    @foreach ($subject->chapters->sortBy('order') as $chapter)
        <x-filament::section :heading="'Bab ' . $chapter->order . ': ' . $chapter->title" :collapsible="true">
            <div class="space-y-4">
                @forelse ($chapter->topics->sortBy('order') as $topic)
                    <x-filament::card>
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold">{{ $topic->title }}</h4>

                            @if ($topic->content_type === 'meeting' && $topic->meeting)
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        Jadwal: {{ $topic->meeting->start_time->format('d M Y, H:i') }}
                                    </span>
                                    <x-filament::button tag="a" :href="route('meetings.show', $topic->meeting)" target="_blank"
                                        icon="heroicon-o-video-camera">
                                        Join Meeting
                                    </x-filament::button>
                                </div>
                            @elseif ($topic->content_type === 'file' && $topic->file_path)
                                <x-filament::button tag="a" :href="Storage::url($topic->file_path)" target="_blank"
                                    icon="heroicon-o-arrow-down-tray" color="gray">
                                    Download Materi
                                </x-filament::button>
                            @endif
                        </div>

                        @if ($topic->content_type === 'text')
                            <div class="prose dark:prose-invert max-w-none mt-4">{!! $topic->content !!}</div>
                        @endif
                    </x-filament::card>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400">Belum ada topik di bab ini.</p>
                @endforelse
            </div>
        </x-filament::section>
    @endforeach
</x-filament-panels::page>
