<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'topic',
        'start_time',
        'duration',
        'zoom_meeting_id',
        'zoom_start_url',
        'zoom_join_url',
        'password',
        // 'teacher_id', // HAPUS
        'zoom_host_id',
        'topic_id', // TAMBAHKAN
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    // Setiap meeting dibuat oleh satu Teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Setiap meeting di-host oleh satu lisensi Zoom
    public function zoomHost()
    {
        return $this->belongsTo(ZoomHost::class, 'zoom_host_id');
    }

    // Meeting ini terhubung ke satu topik materi
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
