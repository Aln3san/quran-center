<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'teacher_id',
        'category',
        'is_active',
    ];

    /**
     * الـ Casts عشان الـ Boolean يرجع صح للفرونت إند
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ========================
    // Relations (العلاقات)
    // ========================

    /**
     * المدرس المسئول عن الدائرة (واحد فقط)
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * الطلاب المشتركين في هذه الدائرة (علاقة متعدد لمتعدد)
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_student')
            ->withPivot(['enrolled_at', 'is_active'])
            ->withTimestamps();
    }

    /**
     * الجدول الأسبوعي الخاص بالدائرة
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * التقييمات والامتحانات اللي تمت في هذه الدائرة
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * سجلات الحضور والغياب اليومية
     */
    public function dailyLogs(): HasMany
    {
        return $this->hasMany(DailyLog::class);
    }

    /**
     * المنشورات (Posts) الخاصة بهذه الدائرة فقط
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
