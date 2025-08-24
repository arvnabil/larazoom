<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class ZoomMeetingController
{
    public function show(Meeting $meeting, Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        // Dapatkan model Topic dari relasi. Kita panggil sebagai method `topic()`
        // untuk menghindari konflik dengan atribut/kolom `topic` yang berupa string.
        $topic = $meeting->topic()->first();
        $teacher = $topic?->chapter?->subject?->teacher;

        // Tentukan role: 1 untuk host (teacher), 0 untuk peserta (student)
        // Host adalah user yang role-nya 'teacher' DAN ID-nya sama dengan teacher yang mengajar subjek ini.
        $actualRole = ($user->role === 'teacher' && $teacher && $user->id === $teacher->id) ? 1 : 0;

        // Tentukan tipe view berdasarkan query parameter, default ke 'component' (embedded).
        $viewType = $request->input('view', 'component');

        // Untuk Component View (embedded), signature HARUS dibuat dengan role 1 (host).
        // SDK akan secara otomatis mengatur pengguna sebagai peserta jika mereka bukan host meeting yang sebenarnya.
        // Untuk Full Page View, kita gunakan role yang sebenarnya.
        $signatureRole = ($viewType !== 'full') ? 1 : $actualRole;

        // Ambil SDK Key & Secret dari .env
        $sdkKey = config('services.zoom.sdk_key');
        $sdkSecret = config('services.zoom.sdk_secret');

        return view('meetings.show', [
            'title' => $meeting->topic,
            'sdkKey' => $sdkKey,
            'meeting_number' => $meeting->zoom_meeting_id,
            'password' => $meeting->password ?? '',
            'userName' => $user->name,
            'userEmail' => $user->email,
            'token' => $this->generateSignature($sdkKey, $sdkSecret, $meeting->zoom_meeting_id, $signatureRole, $viewType), // 'token' is the signature
            'role' => $actualRole,
            'viewType' => $viewType,
        ]);
    }

    private function generateSignature(string $sdkKey, string $sdkSecret, int $meetingNumber, int $role, string $viewType): string
    {
        $iat = time() - 30;
        $exp = $iat + 60 * 60 * 2; // Signature valid selama 2 jam

        $payload = [
            'mn' => $meetingNumber,
            'role' => $role,
            'iat' => $iat,
            'exp' => $exp,
            'tokenExp' => $exp,
        ];

        // Kunci payload untuk SDK Key berbeda tergantung pada tipe view.
        // Ini adalah detail penting yang sering terlewat.
        // Component/Embedded View menggunakan 'appKey'.
        // Client/Full Page View menggunakan 'sdkKey'.
        $payload[$viewType === 'full' ? 'sdkKey' : 'appKey'] = $sdkKey;

        return JWT::encode($payload, $sdkSecret, 'HS256');
    }
}
