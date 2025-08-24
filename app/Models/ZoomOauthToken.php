<?php

namespace App\Models;

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
}
