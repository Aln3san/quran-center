<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyLog extends Model
{
    protected $fillable = [
        'student_id',
        'group_id',
        'log_date',
        'attendance_status',
        'notes',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
        ];
    }

    /** ألوان الحضور للـ GitHub-style squares */
    const STATUS_COLORS = [
        'present' => 'green',
        'absent'  => 'red',
        'excused' => 'yellow',
    ];

    // ========================
    // Relations
    // ========================

    /** الطالب */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /** الدائرة */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /** المدرس اللي سجّل السجل */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ========================
    // Accessors
    // ========================

    /** لون المربع على حسب الحضور */
    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->attendance_status] ?? 'gray';
    }

    // ========================
    // Scopes
    // ========================

    public function scopePresent($query)
    {
        return $query->where('attendance_status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('attendance_status', 'absent');
    }

    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForGroup($query, int $groupId)
    {
        return $query->where('group_id', $groupId);
    }
}