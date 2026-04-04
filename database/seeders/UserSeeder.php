<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ========================
        // Admin
        // ========================
        $admin = User::firstOrCreate(
            ['email' => 'admin@masjid.com'],
            [
                'name'     => 'مدير النظام',
                'password' => Hash::make('password'),
                'phone'    => '01000000000',
            ]
        );
        $admin->assignRole('Admin');

        // ========================
        // Teachers (مدرسين)
        // ========================
        $teachers = [
            ['name' => 'الشيخ أحمد محمد',   'email' => 'ahmed@masjid.com',   'phone' => '01011111111'],
            ['name' => 'الشيخ محمود علي',    'email' => 'mahmoud@masjid.com', 'phone' => '01022222222'],
            ['name' => 'الشيخ عبدالله حسن',  'email' => 'abdullah@masjid.com','phone' => '01033333333'],
        ];

        foreach ($teachers as $data) {
            $teacher = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['password' => Hash::make('password')])
            );
            $teacher->assignRole('Teacher');
        }

        // ========================
        // Students (طلاب) - 20 طالب
        // ========================
        $students = [
            ['name' => 'محمد أحمد السيد',     'email' => 'student1@masjid.com',  'phone' => '01100000001'],
            ['name' => 'عمر خالد إبراهيم',    'email' => 'student2@masjid.com',  'phone' => '01100000002'],
            ['name' => 'يوسف طارق محمد',      'email' => 'student3@masjid.com',  'phone' => '01100000003'],
            ['name' => 'إبراهيم سامي علي',    'email' => 'student4@masjid.com',  'phone' => '01100000004'],
            ['name' => 'عبدالرحمن فتحي',      'email' => 'student5@masjid.com',  'phone' => '01100000005'],
            ['name' => 'زياد هاني مصطفى',     'email' => 'student6@masjid.com',  'phone' => '01100000006'],
            ['name' => 'كريم وليد سعد',       'email' => 'student7@masjid.com',  'phone' => '01100000007'],
            ['name' => 'أنس ماجد رضا',        'email' => 'student8@masjid.com',  'phone' => '01100000008'],
            ['name' => 'حمزة صالح الدين',     'email' => 'student9@masjid.com',  'phone' => '01100000009'],
            ['name' => 'مصطفى عادل نور',      'email' => 'student10@masjid.com', 'phone' => '01100000010'],
            ['name' => 'أحمد جمال الدين',     'email' => 'student11@masjid.com', 'phone' => '01100000011'],
            ['name' => 'عبدالله محمود فاروق', 'email' => 'student12@masjid.com', 'phone' => '01100000012'],
            ['name' => 'علي حسين منصور',      'email' => 'student13@masjid.com', 'phone' => '01100000013'],
            ['name' => 'ياسين تامر شريف',     'email' => 'student14@masjid.com', 'phone' => '01100000014'],
            ['name' => 'إسلام رامي عزيز',     'email' => 'student15@masjid.com', 'phone' => '01100000015'],
            ['name' => 'سيف الدين مجدي',      'email' => 'student16@masjid.com', 'phone' => '01100000016'],
            ['name' => 'نور الدين باسم',      'email' => 'student17@masjid.com', 'phone' => '01100000017'],
            ['name' => 'بلال رضوان قاسم',     'email' => 'student18@masjid.com', 'phone' => '01100000018'],
            ['name' => 'صهيب ناصر الدين',     'email' => 'student19@masjid.com', 'phone' => '01100000019'],
            ['name' => 'عمار سعيد توفيق',     'email' => 'student20@masjid.com', 'phone' => '01100000020'],
        ];

        foreach ($students as $data) {
            $student = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['password' => Hash::make('password')])
            );
            $student->assignRole('Student');
        }

        $this->command->info('✅ Users seeded: 1 admin, 3 teachers, 20 students');
    }
}
