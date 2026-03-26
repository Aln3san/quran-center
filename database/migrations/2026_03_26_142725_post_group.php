<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ربط البوست بالدوائر - المدرس يبعت لـ 3 دوائر في خبطة
        Schema::create('post_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')
                  ->constrained('posts')
                  ->cascadeOnDelete();
            $table->foreignId('group_id')
                  ->constrained('groups')
                  ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['post_id', 'group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_group');
    }
};