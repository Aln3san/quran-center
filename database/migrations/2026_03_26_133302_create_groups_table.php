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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم الدائرة مثل: حلقة التميز');
            $table->foreignId('teacher_id')
                  ->constrained('users')
                  ->restrictOnDelete()
                  ->comment('المدرس المسئول عن الدائرة');
            $table->enum('category', ['دين', 'لغات', 'دراسة', 'قرآن', 'أخرى'])
                  ->default('قرآن')
                  ->comment('نوع النشاط: دين / لغات / دراسة / قرآن');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
