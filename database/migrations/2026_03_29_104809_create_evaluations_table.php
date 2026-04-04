<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();

            $table->string('title')->comment('اسم الاختبار أو الشهر');
            $table->integer('score')->default(0);
            $table->integer('max_score')->default(100);
            $table->date('evaluation_date')->comment('تاريخ الامتحان');
            $table->timestamps();

            // منع تكرار نفس الامتحان لنفس الطالب في نفس المادة
            $table->unique(['student_id', 'group_id', 'title', 'evaluation_date'], 'student_eval_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
