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
        Schema::create('pay_ments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('الطالب اللي دفع');
            $table->foreignId('group_id')
                ->constrained('groups')
                ->restrictOnDelete()
                ->comment('الدائرة اللي الدفع خاصة بيها');
            $table->decimal('amount', 8, 2)->comment('المبلغ بالجنيه');
            $table->tinyInteger('month')
                ->comment('الشهر 1-12');
            $table->year('year')->comment('السنة');
            $table->enum('payment_method', ['cash', 'vodafone_cash'])
                ->default('cash');
            $table->foreignId('received_by')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('الشيخ اللي استلم الكاش');
            $table->text('notes')->nullable();
            $table->timestamps();

            // منع تسجيل نفس الدفعة مرتين
            $table->unique(['student_id', 'group_id', 'month', 'year']);

            $table->index(['student_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_ments');
    }
};
