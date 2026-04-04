<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin   = User::role('Admin')->first();
        $teachers = User::role('Teacher')->get();
        $groups  = Group::all();

        // ========================
        // منشورات عامة (is_global = true)
        // ========================
        $globalPosts = [
            [
                'title'        => 'مرحباً بكم في مركز تحفيظ القرآن الكريم',
                'content'      => 'الحمد لله رب العالمين، نرحب بجميع الطلاب الكرام في مركزنا. نسأل الله أن يجعل هذا العلم نافعاً لكم في الدنيا والآخرة. يسعدنا انضمامكم إلى أسرتنا الكريمة.',
                'is_global'    => true,
                'author_id'    => $admin->id,
                'published_at' => now()->subDays(30),
            ],
            [
                'title'        => 'إعلان هام: جدول الامتحانات الشهرية',
                'content'      => 'نعلمكم بأن الامتحانات الشهرية ستبدأ يوم السبت القادم. يُرجى من جميع الطلاب الاستعداد الجيد ومراجعة محفوظاتهم. بارك الله في جهودكم.',
                'is_global'    => true,
                'author_id'    => $admin->id,
                'published_at' => now()->subDays(7),
            ],
            [
                'title'        => 'تهانينا للطلاب المتميزين هذا الشهر',
                'content'      => 'نبارك لجميع الطلاب الذين أتموا حفظ جزءهم هذا الشهر. جزاكم الله خيراً على مجهوداتكم واجتهادكم. واصلوا مسيرتكم المباركة.',
                'is_global'    => true,
                'author_id'    => $admin->id,
                'published_at' => now()->subDays(3),
            ],
        ];

        foreach ($globalPosts as $data) {
            Post::firstOrCreate(
                ['title' => $data['title']],
                $data
            );
        }

        // ========================
        // منشورات خاصة بدوائر (is_global = false)
        // ========================
        $groupPostsData = [
            [
                'title'     => 'واجب المراجعة لهذا الأسبوع',
                'content'   => 'على جميع طلاب الدائرة مراجعة سورة البقرة من الآية 1 إلى 50 وتكرارها 10 مرات يومياً قبل الحصة القادمة.',
                'group_idx' => 0,
            ],
            [
                'title'     => 'ملاحظات على حصة اليوم',
                'content'   => 'الحمد لله، كانت الحصة ممتازة اليوم. لكن ننبه على ضرورة الانتباه لأحكام التجويد خاصةً المد المتصل والمنفصل.',
                'group_idx' => 0,
            ],
            [
                'title'     => 'موعد الاختبار الشهري للدائرة',
                'content'   => 'سيُعقد اختبار هذا الشهر يوم الأربعاء القادم إن شاء الله. الاختبار سيشمل المقرر كاملاً من أول الشهر.',
                'group_idx' => 1,
            ],
            [
                'title'     => 'تذكير بقواعد التجويد المهمة',
                'content'   => 'تذكير لجميع الطلاب بضرورة تطبيق أحكام النون الساكنة والتنوين: الإظهار، الإدغام، الإقلاب، الإخفاء. سيُسأل عنها في الاختبار القادم.',
                'group_idx' => 2,
            ],
            [
                'title'     => 'نتائج اختبار الشهر الماضي',
                'content'   => 'بارك الله في جميع الطلاب، نتائج الاختبار كانت رائعة. أعلى نسبة كانت 95% وأدنى نسبة 60%. نحتاج مزيداً من التركيز مع الطلاب الذين يحتاجون دعماً إضافياً.',
                'group_idx' => 3,
            ],
        ];

        foreach ($groupPostsData as $data) {
            $teacher = $teachers->get($data['group_idx'] % $teachers->count());
            $group   = $groups->get($data['group_idx']);

            if (!$teacher || !$group) continue;

            $post = Post::firstOrCreate(
                ['title' => $data['title']],
                [
                    'author_id'    => $teacher->id,
                    'content'      => $data['content'],
                    'is_global'    => false,
                    'published_at' => now()->subDays(rand(1, 20)),
                ]
            );

            // ربط البوست بالدائرة
            $post->groups()->syncWithoutDetaching([$group->id]);
        }

        $totalPosts = count($globalPosts) + count($groupPostsData);
        $this->command->info("✅ Posts seeded: {$totalPosts} posts (3 global + " . count($groupPostsData) . ' group-specific)');
    }
}
