<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ZoomOauthToken;
use Carbon\Carbon;

class ZoomOAuthController
{
    public function redirect()
    {
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => config('services.zoom.oauth_client_id'),
            'redirect_uri' => config('services.zoom.oauth_redirect_uri'),
        ]);

        return redirect('https://zoom.us/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(config('services.zoom.oauth_client_id') . ':' . config('services.zoom.oauth_client_secret')),
        ])->asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'redirect_uri' => config('services.zoom.oauth_redirect_uri'),
        ]);

        $tokenData = $response->json();

        ZoomOauthToken::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'],
                'expires_at' => Carbon::now()->addSeconds($tokenData['expires_in']),
            ]
        );

        return redirect('/dashboard')->with('status', 'Akun Zoom berhasil terhubung!');
    }
}
