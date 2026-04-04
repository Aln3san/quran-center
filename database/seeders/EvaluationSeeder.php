<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::with('students')->get();

        if ($groups->isEmpty()) {
            $this->command->warn('⚠️ No groups found. Skipping EvaluationSeeder.');
            return;
        }

        $count = 0;

        // أنواع الاختبارات الشهرية
        $evaluationTypes = [
            ['title' => 'اختبار الحفظ الشهري',      'max_score' => 100],
            ['title' => 'اختبار التجويد',             'max_score' => 50],
            ['title' => 'اختبار المراجعة الأسبوعية', 'max_score' => 20],
            ['title' => 'اختبار التلاوة الجهرية',    'max_score' => 30],
        ];

        foreach ($groups->take(4) as $group) {
            $students = $group->students;

            if ($students->isEmpty()) continue;

            foreach ($evaluationTypes as $evalType) {
                // اختبارات آخر 3 أشهر
                for ($monthsAgo = 3; $monthsAgo >= 1; $monthsAgo--) {
                    $evalDate = now()->subMonths($monthsAgo)->startOfMonth()->addDays(rand(14, 25));

                    foreach ($students as $student) {
                        // درجة واقعية: بين 50% و 100% من الدرجة العظمى
                        $minScore = (int) ($evalType['max_score'] * 0.5);
                        $score    = rand($minScore, $evalType['max_score']);

                        Evaluation::firstOrCreate(
                            [
                                'student_id'      => $student->id,
                                'group_id'        => $group->id,
                                'title'           => $evalType['title'],
                                'evaluation_date' => $evalDate->toDateString(),
                            ],
                            [
                                'score'     => $score,
                                'max_score' => $evalType['max_score'],
                            ]
                        );
                        $count++;
                    }
                }
            }
        }

        $this->command->info("✅ Evaluations seeded: {$count} records");
    }
}
