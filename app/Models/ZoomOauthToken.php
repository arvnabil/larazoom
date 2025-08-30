<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ZoomOauthToken extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Token ini milik satu user (student)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cek apakah token sudah atau akan segera kedaluwarsa.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        // Anggap kedaluwarsa jika akan habis dalam 60 detik ke depan untuk keamanan.
        return $this->expires_at->lt(Carbon::now()->addSeconds(60));
    }
}
