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
        $teacher = User::role('teacher')->first();

        if ($teacher) {
            // Create a subject and assign the teacher to it.
            $subject = Subject::create([
                'name' => 'Mathematics 101',
                'description' => 'An introductory course on fundamental mathematics concepts.',
                'teacher_id' => $teacher->id,
            ]);

            // Find student users
            $students = User::role('student')->get();

            // Attach students to the subject
            if ($students->isNotEmpty() && $subject) {
                $subject->students()->attach($students->pluck('id'));
            }
        }
    }
}
