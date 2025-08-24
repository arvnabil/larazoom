<?php

namespace Database\Seeders;

use App\Models\ZoomHost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZoomHostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ganti 'user_id_from_zoom_1' dengan ID user asli dari akun Zoom Anda
        ZoomHost::create(['name' => 'Lisensi 1', 'zoom_user_id' => 'user_id_from_zoom_1']);
        ZoomHost::create(['name' => 'Lisensi 2', 'zoom_user_id' => 'user_id_from_zoom_2']);
        ZoomHost::create(['name' => 'Lisensi 3', 'zoom_user_id' => 'user_id_from_zoom_3']);
    }
}
