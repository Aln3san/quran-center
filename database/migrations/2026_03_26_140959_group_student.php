<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot: طالب واحد ممكن يكون في أكتر من دائرة
        Schema::create('group_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')
                ->constrained('groups')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('معرف الطالب');
            $table->date('enrolled_at')->useCurrent()->comment('تاريخ الانضمام للدائرة');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // منع التكرار: نفس الطالب في نفس الدائرة مرة واحدة بس
            $table->unique(['group_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_student');
    }
};
