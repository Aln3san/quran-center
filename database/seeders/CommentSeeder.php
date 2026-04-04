<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $posts    = Post::all();
        $students = User::role('Student')->get();
        $teachers = User::role('Teacher')->get();

        if ($posts->isEmpty() || $students->isEmpty()) {
            $this->command->warn('⚠️ No posts or students found. Skipping CommentSeeder.');
            return;
        }

        $count = 0;

        $commentsData = [
            'جزاكم الله خيراً على هذا الإعلان المهم.',
            'بارك الله فيكم، سنستعد جيداً للاختبار.',
            'هل يمكن تحديد المقرر بشكل أوضح من فضلك؟',
            'الحمد لله، نتائج الشهر الماضي كانت جيدة بفضل الله.',
            'شكراً على التذكير يا شيخ.',
            'سؤال: هل الاختبار شفهي أم تحريري؟',
            'اللهم بارك، وفق الله الجميع.',
            'سنكون حاضرين إن شاء الله.',
            'جزاك الله خيراً على المتابعة والاهتمام.',
            'هل يمكن تأجيل الاختبار أسبوعاً لظروف الامتحانات المدرسية؟',
        ];

        $repliesData = [
            'آمين، وإياكم.',
            'بالتوفيق لجميع الطلاب.',
            'نعم سيكون شفهي وتحريري معاً.',
            'إن شاء الله سنأخذ ذلك في الاعتبار.',
            'الاختبار في موعده إن شاء الله.',
        ];

        foreach ($posts->take(5) as $post) {
            // تعليقات جذر (2-3 لكل بوست)
            $commentCount = rand(2, 3);
            $createdComments = [];

            for ($i = 0; $i < $commentCount; $i++) {
                $user = $students->get(($post->id + $i) % $students->count());

                $comment = Comment::create([
                    'post_id'   => $post->id,
                    'user_id'   => $user->id,
                    'content'   => $commentsData[($post->id + $i) % count($commentsData)],
                    'parent_id' => null,
                ]);

                $createdComments[] = $comment;
                $count++;
            }

            // ردود المدرس على بعض التعليقات
            foreach (array_slice($createdComments, 0, 1) as $parentComment) {
                $teacher = $teachers->first();

                Comment::create([
                    'post_id'   => $post->id,
                    'user_id'   => $teacher->id,
                    'content'   => $repliesData[rand(0, count($repliesData) - 1)],
                    'parent_id' => $parentComment->id,
                ]);
                $count++;
            }
        }

        $this->command->info("✅ Comments seeded: {$count} comments & replies");
    }
}
