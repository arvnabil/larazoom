<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'description', 'teacher_id'];

    // Mata pelajaran ini diajar oleh satu guru
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Satu mata pelajaran memiliki banyak bab
    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    // Satu mata pelajaran bisa memiliki banyak siswa
    public function students()
    {
        return $this->belongsToMany(User::class, 'subject_user');
    }
}
