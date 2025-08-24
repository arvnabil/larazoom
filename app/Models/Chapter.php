<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = ['subject_id', 'title', 'order'];

    // Bab ini milik satu mata pelajaran
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Satu bab memiliki banyak topik/materi
    public function topics()
    {
        return $this->hasMany(Topic::class)->orderBy('order');
    }
}
