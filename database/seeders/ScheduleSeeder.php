<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::all();

        // جدول جاهز لكل دائرة
        $scheduleTemplates = [
            // دائرة 0: السبت والاثنين والأربعاء
            [
                ['day_of_week' => 'saturday',  'start_time' => '09:00', 'end_time' => '11:00'],
                ['day_of_week' => 'monday',    'start_time' => '09:00', 'end_time' => '11:00'],
                ['day_of_week' => 'wednesday', 'start_time' => '09:00', 'end_time' => '11:00'],
            ],
            // دائرة 1: الأحد والثلاثاء والخميس
            [
                ['day_of_week' => 'sunday',   'start_time' => '10:00', 'end_time' => '12:00'],
                ['day_of_week' => 'tuesday',  'start_time' => '10:00', 'end_time' => '12:00'],
                ['day_of_week' => 'thursday', 'start_time' => '10:00', 'end_time' => '12:00'],
            ],
            // دائرة 2: السبت والأربعاء (مسائي)
            [
                ['day_of_week' => 'saturday',  'start_time' => '17:00', 'end_time' => '19:00'],
                ['day_of_week' => 'wednesday', 'start_time' => '17:00', 'end_time' => '19:00'],
            ],
            // دائرة 3: الاثنين والخميس (مسائي)
            [
                ['day_of_week' => 'monday',   'start_time' => '18:00', 'end_time' => '20:00'],
                ['day_of_week' => 'thursday', 'start_time' => '18:00', 'end_time' => '20:00'],
            ],
            // دائرة 4: الثلاثاء والجمعة
            [
                ['day_of_week' => 'tuesday', 'start_time' => '08:00', 'end_time' => '10:00'],
                ['day_of_week' => 'friday',  'start_time' => '08:00', 'end_time' => '09:30'],
            ],
            // دائرة 5: الأحد والأربعاء
            [
                ['day_of_week' => 'sunday',    'start_time' => '16:00', 'end_time' => '17:30'],
                ['day_of_week' => 'wednesday', 'start_time' => '16:00', 'end_time' => '17:30'],
            ],
        ];

        $count = 0;

        foreach ($groups as $index => $group) {
            $template = $scheduleTemplates[$index % count($scheduleTemplates)];

            foreach ($template as $slot) {
                Schedule::firstOrCreate(
                    [
                        'group_id'    => $group->id,
                        'day_of_week' => $slot['day_of_week'],
                    ],
                    [
                        'start_time' => $slot['start_time'],
                        'end_time'   => $slot['end_time'],
                    ]
                );
                $count++;
            }
        }

        $this->command->info("✅ Schedules seeded: {$count} time slots");
    }
}
