<?php
namespace App\Services;

use App\Models\User;
use App\Models\ZoomOauthToken;
use Carbon\Carbon;
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

    /**
     * Mendapatkan ZAK token untuk seorang user (siswa).
     *
     * @param User $user
     * @return string|null
     */
    public function getUserZakToken(User $user): ?string
    {
        $oauthToken = $user->zoomOauthToken;

        // Jika user belum menghubungkan akun Zoom-nya.
        if (!$oauthToken) {
            return null;
        }

        // Refresh token jika sudah kedaluwarsa.
        $accessToken = $this->refreshUserOAuthToken($oauthToken);
        if (!$accessToken) {
            return null; // Gagal refresh.
        }

        // Dapatkan ZAK token dari Zoom API.
        // Endpointnya adalah /users/me/token, yang merujuk ke user pemilik access token.
        $response = Http::withToken($accessToken)
            ->get("https://api.zoom.us/v2/users/me/token", ['type' => 'zak']);

        if ($response->successful()) {
            return $response->json('token');
        }

        Log::error('Failed to get ZAK token for user: ' . $user->id, $response->json());
        return null;
    }

    /**
     * Memperbarui OAuth token pengguna jika sudah kedaluwarsa.
     *
     * @param ZoomOauthToken $token
     * @return string|null Access token yang valid.
     */
    private function refreshUserOAuthToken(ZoomOauthToken $token): ?string
    {
        if (!$token->isExpired()) {
            return $token->access_token;
        }

        Log::info('Refreshing Zoom OAuth token for user: ' . $token->user_id);

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(config('services.zoom.oauth_client_id') . ':' . config('services.zoom.oauth_client_secret')),
        ])->asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $token->refresh_token,
        ]);

        if ($response->failed()) {
            Log::error('Failed to refresh Zoom OAuth token for user: ' . $token->user_id, $response->json());
            // Jika refresh token tidak valid, hapus token agar user bisa re-autentikasi.
            $token->delete();
            return null;
        }

        $tokenData = $response->json();

        // Perbarui token di database dengan yang baru.
        $token->update([
            'access_token' => $tokenData['access_token'],
            'refresh_token' => $tokenData['refresh_token'],
            'expires_at' => Carbon::now()->addSeconds($tokenData['expires_in']),
        ]);

        return $tokenData['access_token'];
    }
}
