<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    /**
     * Mendapatkan Server-to-Server OAuth token dari Zoom.
     * Token ini akan di-cache untuk efisiensi.
     *
     * @return string|null
     */
    private function getS2SToken()
    {
        // Coba ambil token dari cache terlebih dahulu.
        return Cache::remember('zoom_s2s_token', 3500, function () {
            $accountId = config('zoom.s2s_account_id');
            $clientId = config('zoom.s2s_client_id');
            $clientSecret = config('zoom.s2s_client_secret');

            if (!$accountId || !$clientId || !$clientSecret) {
                Log::error('Zoom S2S credentials are not configured.');
                return null;
            }

            $response = Http::withBasicAuth($clientId, $clientSecret)
                ->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id={$accountId}");

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('Failed to obtain Zoom S2S token.', $response->json());
            return null;
        });
    }

    public function createMeeting(string $zoomUserId, string $topic, string $startTime, int $duration): ?array
    {
        $token = $this->getS2SToken();
        if (!$token) {
            return null; // Gagal mendapatkan token, hentikan proses.
        }

        $response = Http::withToken($token)->post("https://api.zoom.us/v2/users/{$zoomUserId}/meetings", [
            'topic' => $topic,
            'type' => 2, // Scheduled meeting
            'start_time' => $startTime,
            'duration' => $duration,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Zoom API - Failed to create meeting.', ['response' => $response->json()]);
        return null;
    }
}
