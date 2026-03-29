<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'group_id',
        'amount',
        'month',
        'year',
        'payment_method',
        'received_by',
        'notes',
    ];

    /**
     * الـ Casts مهمة جداً هنا للفلوس والتواريخ
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'month'  => 'integer',
            'year'   => 'integer',
        ];
    }

    // ========================
    // Relations (العلاقات)
    // ========================

    /** الطالب اللي دفع */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /** الدائرة اللي الدفع مخصص ليها */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /** الشيخ أو الموظف اللي استلم الفلوس */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}