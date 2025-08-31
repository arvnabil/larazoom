<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Services\ZoomService;
use Filament\Notifications\Notification;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ZoomMeetingController
{
    public function show(Meeting $meeting, Request $request, ZoomService $zoomService)
    {
        // --- Pengecekan Waktu Meeting ---
        // Hitung waktu berakhirnya meeting. `start_time` sudah di-cast ke Carbon.
        $endTime = $meeting->start_time->addMinutes($meeting->duration);
        /** @var User $user */
        $user = auth()->user();


        // Jika waktu saat ini sudah melewati waktu berakhirnya meeting
        if (now()->gt($endTime)) {
            return Notification::make()
                ->title('Meeting Sudah Selesai')
                ->body('Meeting ini sudah selesai dan tidak bisa diakses lagi.')
                ->warning()
                ->toDatabase($user);

        }

        // Dapatkan guru yang seharusnya menjadi host untuk meeting ini dari relasi.
        $meetingTeacher = $meeting->topicModel?->chapter?->subject?->teacher;

        // // Tentukan role: 1 untuk host (teacher), 0 untuk peserta (student)
        // // Host adalah user yang role-nya 'teacher' DAN ID-nya sama dengan teacher yang mengajar subjek ini.
        // $actualRole = ($user->hasRole('teacher') && $teacher && $user->id === $teacher->id) ? 1 : 0;
        // // Tentukan tipe view berdasarkan query parameter, default ke 'component' (embedded).
        // $viewType = $request->input('view', 'component');
        // // Gunakan role yang sebenarnya (1 untuk host, 0 untuk peserta) untuk membuat signature,
        // // tidak peduli tipe view-nya. Ini adalah pendekatan yang paling benar sesuai dokumentasi Zoom.
        // $signatureRole = $actualRole;

        // Role untuk signature Zoom: 1 untuk host, 0 untuk peserta (student).
        // Ini adalah bagian krusial yang menentukan hak akses di dalam meeting.
        // $signatureRole = $isHost ? 1 : 0;

        // Tentukan apakah user yang sedang login adalah host dari meeting ini.
        // Syaratnya: user harus punya role 'teacher' DAN ID-nya cocok dengan guru pengajar.
        // Syaratnya:
        // 1. User adalah guru yang ditugaskan untuk mata pelajaran ini.
        // ATAU
        // 2. User adalah super_admin (untuk keperluan administrasi dari panel).
        $isDesignatedTeacher = $user->hasRole('teacher') && $meetingTeacher && $user->id === $meetingTeacher->id;
        $isSuperAdmin = $user->hasRole('super_admin');
        $isHost = $isDesignatedTeacher || $isSuperAdmin;

        // Role untuk signature Zoom: 1 untuk host, 0 untuk peserta (student).
        // Ini adalah bagian krusial yang menentukan hak akses di dalam meeting.
        $signatureRole = $isHost ? 1 : 0;

        $zakToken = null;
        // Hanya peserta (bukan host) yang memerlukan ZAK token untuk bergabung.
        // ZAK token memungkinkan siswa untuk melewati layar login Zoom jika akun mereka sudah terhubung via OAuth.
        // Guru (host) tidak memerlukan ZAK token untuk memulai meeting via SDK, karena signature dengan role 1 sudah cukup.
        // Inilah mekanisme "server-to-server" yang Anda maksud untuk host.
        if (!$isHost) {
            $zakToken = $zoomService->getUserZakToken($user);
        }

        // Ambil SDK Key & Secret dari .env
        $sdkKey = config('services.zoom.sdk_key');
        $sdkSecret = config('services.zoom.sdk_secret');

         // Tentukan tipe view: 'component' (embedded) atau 'full' (halaman penuh)
        $viewType = $request->input('view', 'component');

        return view('meetings.show', [
            'title' => $meeting->topic,
            'sdkKey' => $sdkKey,
            'meeting_number' => $meeting->zoom_meeting_id,
            'password' => $meeting->password ?? '',
            'userName' => $user->name,
            'userEmail' => $user->email,
            // Signature ini adalah "kunci" untuk masuk ke meeting.
            // Dibuat di server menggunakan SDK Secret dan dikirim ke client.
            // Signature dengan role=1 akan menjadikan user sebagai host.
            'signature' => $this->generateSignature($sdkKey, $sdkSecret, $meeting->zoom_meeting_id, $signatureRole),
            'zakToken' => $zakToken ?? '', // Kirim ZAK token ke view
            'role' => $signatureRole, // Digunakan oleh view Blade untuk logika UI
            'viewType' => $viewType,
        ]);
    }

    private function generateSignature(string $sdkKey, string $sdkSecret, int $meetingNumber, int $role): string
    {
        $iat = time();
        $exp = $iat + 60 * 60 * 2;
        $payload = [
            'sdkKey' => $sdkKey, // Untuk Web SDK v2.x.x, 'sdkKey' digunakan untuk semua view.
            'mn' => $meetingNumber,
            'role' => $role,
            'iat' => $iat,
            'exp' => $exp,
            'tokenExp' => $exp
        ];

        return JWT::encode($payload, $sdkSecret, 'HS256');
    }
}
