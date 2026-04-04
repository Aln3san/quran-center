<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupStudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::role('Student')->get();
        $groups   = Group::all();

        if ($students->isEmpty() || $groups->isEmpty()) {
            $this->command->warn('⚠️ No students or groups found. Skipping GroupStudentSeeder.');
            return;
        }

        // توزيع الطلاب على الدوائر
        // كل طالب يكون في دائرة أو اثنتين على الأقل
        $enrollmentCount = 0;

        foreach ($students as $index => $student) {
            // الدائرة الأساسية بناءً على رقم الطالب
            $primaryGroup = $groups->get($index % $groups->count());

            $student->groups()->syncWithoutDetaching([
                $primaryGroup->id => [
                    'enrolled_at' => now()->subMonths(rand(1, 12))->toDateString(),
                    'is_active'   => true,
                ],
            ]);
            $enrollmentCount++;

            // نص الطلاب يكونوا في دائرة ثانية
            if ($index % 3 === 0) {
                $secondaryGroup = $groups->get(($index + 2) % $groups->count());

                if ($secondaryGroup->id !== $primaryGroup->id) {
                    $student->groups()->syncWithoutDetaching([
                        $secondaryGroup->id => [
                            'enrolled_at' => now()->subMonths(rand(1, 6))->toDateString(),
                            'is_active'   => true,
                        ],
                    ]);
                    $enrollmentCount++;
                }
            }
        }

        $this->command->info("✅ Group-Student enrollments seeded: {$enrollmentCount} records");
    }
}
