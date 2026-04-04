<?php

namespace Database\Seeders;

use App\Models\DailyLog;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class DailyLogSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::role('Teacher')->get();
        $groups   = Group::with('students')->get();

        if ($groups->isEmpty()) {
            $this->command->warn('⚠️ No groups found. Skipping DailyLogSeeder.');
            return;
        }

        $statuses = ['present', 'present', 'present', 'absent', 'excused']; // توزيع واقعي: 60% حضور
        $count    = 0;

        foreach ($groups->take(4) as $group) {
            $students = $group->students;

            if ($students->isEmpty()) continue;

            $teacher = $teachers->get($group->id % $teachers->count()) ?? $teachers->first();

            // سجلات آخر 4 أسابيع (28 يوم)
            for ($daysAgo = 28; $daysAgo >= 1; $daysAgo--) {
                $date = now()->subDays($daysAgo)->toDateString();

                // تخطي الجمعة (يوم إجازة)
                $dayOfWeek = now()->subDays($daysAgo)->dayOfWeek;
                if ($dayOfWeek === 5) continue; // 5 = Friday

                foreach ($students as $student) {
                    // تجنب التكرار
                    $exists = DailyLog::where('student_id', $student->id)
                        ->where('group_id', $group->id)
                        ->where('log_date', $date)
                        ->exists();

                    if ($exists) continue;

                    $status = $statuses[array_rand($statuses)];

                    DailyLog::updateOrCreate(
                        [
                            // 1. لارافيل بيدور بدول بس (المفاتيح الفريدة)
                            'student_id' => $student->id,
                            'group_id'   => $group->id,
                            'log_date'   => $date,
                        ],
                        [
                            // 2. دول بيتكتبوا "فقط" لو السجل مش موجود
                            'attendance_status' => $status,
                            'notes'             => $status === 'absent' ? 'غياب بدون إذن' : ($status === 'excused' ? 'إجازة مرضية' : null),
                            'recorded_by'       => $teacher->id,
                        ]
                    );

                    $count++;
                }
            }
        }

        $this->command->info("✅ DailyLogs seeded: {$count} attendance records (last 28 days)");
    }
}
