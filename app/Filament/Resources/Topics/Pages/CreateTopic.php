<?php

namespace App\Filament\Resources\Topics\Pages;

use App\Filament\Resources\Topics\TopicResource;
use App\Models\ZoomHost;
use App\Services\ZoomService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateTopic extends CreateRecord
{
    protected static string $resource = TopicResource::class;

     /**
     * Cari host Zoom yang tersedia pada slot waktu tertentu.
     */
    private static function findAvailableZoomHost(Carbon $startTime, int $duration): ?ZoomHost
    {
        $endTime = $startTime->clone()->addMinutes($duration);

        return ZoomHost::whereDoesntHave('meetings', function ($query) use ($startTime, $endTime) {
            $query->where(function ($q) use ($startTime, $endTime) {
                // Cek tumpang tindih: (StartA < EndB) and (EndA > StartB)
                $q->where('start_time', '<', $endTime)
                  ->whereRaw('start_time + INTERVAL duration MINUTE > ?', [$startTime]);
            });
        })->first();
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Jika bukan meeting, biarkan proses pembuatan default Filament yang berjalan.
        // Filament akan secara otomatis mengisi field 'content' atau 'file_path'.
        if ($data['content_type'] !== 'meeting') {
            return static::getModel()::create($data);
        }

        // --- LOGIKA KHUSUS UNTUK MEMBUAT MEETING ---
        $zoomService = app(ZoomService::class);
        $startTime = Carbon::parse($data['start_time']);
        $duration = (int) $data['duration'];

        $availableHost = self::findAvailableZoomHost($startTime, $duration);

        if (!$availableHost) {
            Notification::make()
                ->title('Gagal Membuat Jadwal')
                ->body('Semua lisensi Zoom sedang digunakan pada waktu yang dipilih. Silakan pilih waktu lain.')
                ->danger()
                ->send();

            throw new Halt();
        }

        $meetingData = $zoomService->createMeeting(
            $availableHost->zoom_user_id,
            $data['title'],
            $startTime->utc()->toIso8601String(), // Zoom API memerlukan format ISO 8601 UTC
            $duration
        );

        if (!$meetingData) {
            Notification::make()
                ->title('Gagal Terhubung ke Zoom')
                ->body('Terjadi kesalahan saat membuat meeting di platform Zoom. Coba beberapa saat lagi.')
                ->danger()
                ->send();

            throw new Halt();
        }

        // Gunakan transaksi database untuk memastikan Topic dan Meeting dibuat bersamaan.
        return DB::transaction(function () use ($data, $meetingData, $availableHost, $startTime, $duration) {
            // Buat Topic terlebih dahulu.
            $topic = static::getModel()::create([
                'chapter_id' => $data['chapter_id'],
                'title' => $data['title'],
                'order' => $data['order'],
                'content_type' => $data['content_type'],
            ]);

            // Buat meeting yang berelasi dengan topic yang baru dibuat.
            $topic->meeting()->create([
                'zoom_host_id' => $availableHost->id,
                'topic' => $data['title'],
                'start_time' => $startTime,
                'duration' => $duration,
                'zoom_meeting_id' => $meetingData['id'],
                'zoom_start_url' => $meetingData['start_url'],
                'zoom_join_url' => $meetingData['join_url'],
            ]);

            return $topic;
        });
    }
}
