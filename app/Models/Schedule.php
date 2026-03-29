<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'group_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time'   => 'datetime:H:i',
        ];
    }

    const DAYS_AR = [
        'saturday'  => 'السبت',
        'sunday'    => 'الأحد',
        'monday'    => 'الاثنين',
        'tuesday'   => 'الثلاثاء',
        'wednesday' => 'الأربعاء',
        'thursday'  => 'الخميس',
        'friday'    => 'الجمعة',
    ];

    // ========================
    // Relations
    // ========================

    /** الدائرة */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    // ========================
    // Accessors
    // ========================

    /** اسم اليوم بالعربي */
    public function getDayArAttribute(): string
    {
        return self::DAYS_AR[$this->day_of_week] ?? $this->day_of_week;
    }
}