<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    protected $fillable = [
        'student_id',
        'group_id',
        'title',
        'score',
        'max_score',
        'evaluation_date',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_date' => 'date',
            'score'           => 'integer',
            'max_score'       => 'integer',
        ];
    }

    // ========================
    // Relations
    // ========================

    /** الطالب */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /** الدائرة / المادة */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    // ========================
    // Accessors
    // ========================

    /** النسبة المئوية */
    public function getPercentageAttribute(): float
    {
        if ($this->max_score === 0) return 0;
        return round(($this->score / $this->max_score) * 100, 2);
    }

    /** هل ناجح؟ (50% فأكثر) */
    public function getIsPassedAttribute(): bool
    {
        return $this->percentage >= 50;
    }
}