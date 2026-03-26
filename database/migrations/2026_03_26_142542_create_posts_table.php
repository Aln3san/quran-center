<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // بديل الواتساب
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')
                  ->constrained('users')
                  ->cascadeOnDelete()
                  ->comment('صاحب البوست - شيخ أو مدرس');
            $table->string('title');
            $table->text('content');
            $table->boolean('is_global')
                  ->default(false)
                  ->comment('true=يظهر للكل | false=لدوائر معينة بس');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['author_id', 'is_global']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};