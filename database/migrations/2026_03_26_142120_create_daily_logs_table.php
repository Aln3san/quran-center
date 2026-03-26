<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // "الجوكر" - ده اللي بيتفك لمربعات GitHub Style
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->cascadeOnDelete()
                  ->comment('معرف الطالب');
            $table->foreignId('group_id')
                  ->constrained('groups')
                  ->cascadeOnDelete()
                  ->comment('مهم جداً - يفصل مربعات كل مادة عن التانية');
            $table->date('log_date')->comment('تاريخ يوم الدرس');
            $table->enum('attendance_status', ['present', 'absent', 'excused'])
                  ->default('present')
                  ->comment('present=أخضر / absent=أحمر / excused=أصفر');
            $table->text('notes')->nullable()
                  ->comment('الإنجاز + الملاحظة الشخصية لليوم ده');
            $table->foreignId('recorded_by')
                  ->constrained('users')
                  ->restrictOnDelete()
                  ->comment('المدرس اللي سجل السجل');
            $table->timestamps();

            // منع التكرار: سجل واحد لكل طالب في كل دائرة كل يوم
            $table->unique(['student_id', 'group_id', 'log_date']);

            // Index للسرعة في عرض المربعات
            $table->index(['student_id', 'group_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};