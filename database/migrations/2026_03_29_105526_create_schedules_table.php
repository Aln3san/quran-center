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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')
            ->constrained('groups');
            $table->enum('day_of_week', ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'])
            ->comment('يوم الأسبوع');
            $table->time('start_time')
            ->comment('وقت البدء');
            $table->time('end_time')
            ->comment('وقت الانتهاء');
            $table->timestamps();

            $table->unique(['group_id', 'day_of_week', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
