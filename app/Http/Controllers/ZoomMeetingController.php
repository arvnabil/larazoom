<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Services\ZoomService;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class ZoomMeetingController
{
    public function show(Meeting $meeting, Request $request, ZoomService $zoomService)
    {
        /** @var User $user */
        $user = auth()->user();

        // Dapatkan model Topic dari relasi. Kita panggil sebagai method `topic()`
        // untuk menghindari konflik dengan atribut/kolom `topic` yang berupa string.
        $topicModel = $meeting->topicModel()->first();
        $teacher = $topicModel?->chapter?->subject?->teacher;

        // Tentukan role: 1 untuk host (teacher), 0 untuk peserta (student)
        // Host adalah user yang role-nya 'teacher' DAN ID-nya sama dengan teacher yang mengajar subjek ini.
        $actualRole = ($user->hasRole('teacher') && $teacher && $user->id === $teacher->id) ? 1 : 0;
        // Tentukan tipe view berdasarkan query parameter, default ke 'component' (embedded).
        $viewType = $request->input('view', 'component');
        // Gunakan role yang sebenarnya (1 untuk host, 0 untuk peserta) untuk membuat signature,
        // tidak peduli tipe view-nya. Ini adalah pendekatan yang paling benar sesuai dokumentasi Zoom.
        $signatureRole = $actualRole;

        $zakToken = null;
        // Hanya siswa (role 0) yang perlu ZAK token untuk bergabung.
        // Guru (host) akan menggunakan start_url atau signature host.
        if ($actualRole === 0) {
            $zakToken = $zoomService->getUserZakToken($user);
        }

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
            'signature' => $this->generateSignature($sdkKey, $sdkSecret, $meeting->zoom_meeting_id, $signatureRole, $viewType),
            'zakToken' => $zakToken ?? '', // Kirim ZAK token ke view
            'role' => $actualRole,
            'viewType' => $viewType,
        ]);
    }

    private function generateSignature(string $sdkKey, string $sdkSecret, int $meetingNumber, int $role, string $viewType): string
    {
        $iat = time();
        $exp = $iat + 60 * 60 * 2;
        $payload = [
            'mn' => $meetingNumber,
            'role' => $role,
            'iat' => $iat,
            'exp' => $exp,
            'tokenExp' => $exp
        ];

        // Kunci payload untuk SDK Key berbeda tergantung pada tipe view. Ini adalah
        // detail penting yang sering terlewat.
        if ($viewType === 'full') {
            $payload['sdkKey'] = $sdkKey; // Client/Full Page View menggunakan 'sdkKey'.
        } else {
            $payload['appKey'] = $sdkKey; // Component/Embedded View menggunakan 'appKey'.
        }

        return JWT::encode($payload, $sdkSecret, 'HS256');
    }
}
