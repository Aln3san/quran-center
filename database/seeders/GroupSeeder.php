<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::role('Teacher')->get();

        $groups = [
            [
                'name'       => 'دائرة تحفيظ القرآن - المستوى الأول',
                'category'   => 'قرآن',
                'is_active'  => true,
                'teacher_id' => $teachers->get(0)?->id,
            ],
            [
                'name'       => 'دائرة تحفيظ القرآن - المستوى الثاني',
                'category'   => 'قرآن',
                'is_active'  => true,
                'teacher_id' => $teachers->get(0)?->id,
            ],
            [
                'name'       => 'دائرة التجويد المتقدم',
                'category'   => 'قرآن',
                'is_active'  => true,
                'teacher_id' => $teachers->get(1)?->id,
            ],
            [
                'name'       => 'دائرة أحكام التجويد للمبتدئين',
                'category'   => 'قرآن',
                'is_active'  => true,
                'teacher_id' => $teachers->get(1)?->id,
            ],
            [
                'name'       => 'دائرة الفقه الإسلامي',
                'category'   => 'دين',
                'is_active'  => true,
                'teacher_id' => $teachers->get(2)?->id,
            ],
            [
                'name'       => 'دائرة السيرة النبوية',
                'category'   => 'دين',
                'is_active'  => false,  // دائرة متوقفة
                'teacher_id' => $teachers->get(2)?->id,
            ],
            [
                'name'       => 'الرياضيات الصف الثالث الاعدادي',
                'category'   => 'دراسة',
                'is_active'  => true,
                'teacher_id' => $teachers->get(2)?->id,
            ],
        ];

        foreach ($groups as $data) {
            Group::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        $this->command->info('✅ Groups seeded: ' . count($groups) . ' groups');
    }
}
