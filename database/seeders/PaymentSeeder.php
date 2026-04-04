<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $groups  = Group::with('students')->where('is_active', true)->get();
        $admin   = User::role('Admin')->first();
        $teachers = User::role('Teacher')->get();

        if ($groups->isEmpty()) {
            $this->command->warn('⚠️ No active groups found. Skipping PaymentSeeder.');
            return;
        }

        $paymentMethods = ['cash', 'vodafone_cash'];
        $count          = 0;

        foreach ($groups as $group) {
            $students = $group->students;

            if ($students->isEmpty()) continue;

            // المدرس المسئول أو الأدمن يستلم الفلوس
            $receiver = $teachers->get($group->id % $teachers->count()) ?? $admin;

            // دفع آخر 3 أشهر
            $currentMonth = now()->month;
            $currentYear  = now()->year;

            for ($i = 3; $i >= 1; $i--) {
                $targetDate  = now()->subMonths($i);
                $month       = $targetDate->month;
                $year        = $targetDate->year;

                foreach ($students as $student) {
                    // 80% من الطلاب دفعوا، 20% لسه
                    if (rand(1, 10) <= 2) continue;

                    $exists = Payment::where('student_id', $student->id)
                        ->where('group_id', $group->id)
                        ->where('month', $month)
                        ->where('year', $year)
                        ->exists();

                    if ($exists) continue;

                    Payment::create([
                        'student_id'     => $student->id,
                        'group_id'       => $group->id,
                        'amount'         => rand(1, 3) * 50, // 50 أو 100 أو 150 جنيه
                        'month'          => $month,
                        'year'           => $year,
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'received_by'    => $receiver->id,
                        'notes'          => null,
                    ]);
                    $count++;
                }
            }

            // الشهر الحالي - بعض الطلاب دفعوا
            foreach ($students->take(3) as $student) {
                $exists = Payment::where('student_id', $student->id)
                    ->where('group_id', $group->id)
                    ->where('month', $currentMonth)
                    ->where('year', $currentYear)
                    ->exists();

                if (!$exists) {
                    Payment::create([
                        'student_id'     => $student->id,
                        'group_id'       => $group->id,
                        'amount'         => 100,
                        'month'          => $currentMonth,
                        'year'           => $currentYear,
                        'payment_method' => 'cash',
                        'received_by'    => $receiver->id,
                        'notes'          => 'دفعة الشهر الحالي',
                    ]);
                    $count++;
                }
            }
        }

        $this->command->info("✅ Payments seeded: {$count} payment records");
    }
}
