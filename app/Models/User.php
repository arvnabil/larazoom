<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Seorang Teacher bisa membuat banyak meeting
    // public function createdMeetings()
    // {
    //     return $this->hasMany(Meeting::class, 'teacher_id');
    // }

    // Seorang Student memiliki satu token OAuth
    public function zoomOauthToken()
    {
        return $this->hasOne(ZoomOauthToken::class);
    }

    // Ganti createdMeetings menjadi subjects
    // Seorang Teacher bisa mengajar banyak mata pelajaran
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }
}
