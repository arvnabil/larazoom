<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@lmslara.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Teacher
        User::create([
            'name' => 'Teacher Budi',
            'email' => 'teacher@lmslara.test',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // Students
        User::create([
            'name' => 'Student Ani',
            'email' => 'student1@lmslara.test',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
        User::create([
            'name' => 'Student Citra',
            'email' => 'student2@lmslara.test',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
    }
}
