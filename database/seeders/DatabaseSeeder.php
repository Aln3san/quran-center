<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // RolesAndPermissionsSeeder::class,        // 1. الأدوار أولاً
            // UserSeeder::class,         // 2. المستخدمين
            StudentProfileSeeder::class, // 3. بروفايلات الطلاب
            // GroupSeeder::class,        // 4. الدوائر
            GroupStudentSeeder::class, // 5. ربط الطلاب بالدوائر
            ScheduleSeeder::class,     // 6. الجداول الدراسية
            PostSeeder::class,         // 7. المنشورات
            CommentSeeder::class,      // 8. التعليقات
            DailyLogSeeder::class,     // 9. سجلات الحضور
            EvaluationSeeder::class,   // 10. التقييمات
            PaymentSeeder::class,      // 11. المدفوعات
        ]);
    }
}
