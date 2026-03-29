<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'grade_level',
        'join_date',
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
        ];
    }

    /** القيم المسموح بيها في grade_level */
    const GRADE_LEVELS = [
        'first_preparatory'  => 'الأول الإعدادي',
        'second_preparatory' => 'الثاني الإعدادي',
        'third_preparatory'  => 'الثالث الإعدادي',
        'first_secondary'    => 'الأول الثانوي',
        'second_secondary'   => 'الثاني الثانوي',
        'third_secondary'    => 'الثالث الثانوي',
    ];

    // ========================
    // Relations
    // ========================

    /** الطالب صاحب البروفايل */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========================
    // Accessors
    // ========================

    /** اسم المرحلة بالعربي */
    public function getGradeLabelAttribute(): string
    {
        return self::GRADE_LEVELS[$this->grade_level] ?? $this->grade_level;
    }
}