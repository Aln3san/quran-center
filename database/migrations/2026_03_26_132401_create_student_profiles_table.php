<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete()
                  ->comment('الطالب في جدول users');
            $table->enum('grade_level', [
                'first_preparatory', 'second_preparatory', 'third_preparatory',
                'first_secondary', 'second_secondary', 'third_secondary',
            ])->nullable()->comment('المرحلة الدراسية');
            $table->date('join_date')->comment('تاريخ انضمام الطالب للمركز');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};