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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable()->comment('رقم ولي الأمر للرسايل');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable()->comment('آخر وقت دخول');
            $table->timestamps();
            $table->softDeletes();
        });
 
        // جدول الـ Tokens الخاص بـ Sanctum
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');                        // user_id + user_type
            $table->string('name')->comment('اسم الـ token مثل: mobile / web');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable()->comment('صلاحيات الـ token');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->comment('انتهاء صلاحية الـ token');
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('users');
    }
};
