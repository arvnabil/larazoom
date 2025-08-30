<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat role 'super_admin', 'teacher', dan 'student'
        // Shield secara otomatis memberikan semua izin ke super_admin
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);

        // Buat user Super Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@lmslara.test',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($superAdminRole);

        // Buat user Teacher
        $teacher = User::create([
            'name' => 'Teacher Budi',
            'email' => 'teacher@lmslara.test',
            'password' => Hash::make('password'),
        ]);
        $teacher->assignRole($teacherRole);

        // Buat user Student
        User::create([
            'name' => 'Student Ani',
            'email' => 'student1@lmslara.test',
            'password' => Hash::make('password'),
        ])->assignRole($studentRole);

        User::create([
            'name' => 'Student Citra',
            'email' => 'student2@lmslara.test',
            'password' => Hash::make('password'),
        ])->assignRole($studentRole);
    }
}
