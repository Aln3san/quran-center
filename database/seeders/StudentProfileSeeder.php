<?php

namespace Database\Seeders;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentProfileSeeder extends Seeder
{
    public function run(): void
    {
        $gradeLevels = array_keys(StudentProfile::GRADE_LEVELS);

        // بنجيب كل اليوزرات اللي عندهم دور student
        $students = User::role('Student')->get();

        foreach ($students as $index => $student) {
            StudentProfile::firstOrCreate(
                ['user_id' => $student->id],
                [
                    'grade_level' => $gradeLevels[$index % count($gradeLevels)],
                    'join_date'   => now()->subMonths(rand(1, 24))->toDateString(),
                ]
            );
        }

        $this->command->info('✅ StudentProfiles seeded: ' . $students->count() . ' profiles');
    }
}
