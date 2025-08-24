<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{

    protected $fillable = ['chapter_id', 'title', 'content_type', 'content', 'file_path', 'order'];

    // Topik ini milik satu bab
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    // Jika topik ini adalah meeting, ia memiliki satu jadwal meeting
    public function meeting()
    {
        return $this->hasOne(Meeting::class);
    }
}
