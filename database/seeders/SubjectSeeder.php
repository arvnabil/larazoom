<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the teacher user created by UserSeeder
        $teacher = User::where('role', 'teacher')->first();

        if ($teacher) {
            // Create a subject and assign the teacher to it.
            // This will have id=1 on a fresh database.
            Subject::create([
                'name' => 'Mathematics 101',
                'description' => 'An introductory course on fundamental mathematics concepts.',
                'teacher_id' => $teacher->id,
            ]);
        }
    }
}
