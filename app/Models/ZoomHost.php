<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZoomHost extends Model
{
    protected $fillable = ['name', 'zoom_user_id', 'is_active'];

    // Satu lisensi bisa menjadi host untuk banyak meeting
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}
