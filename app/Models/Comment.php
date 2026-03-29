<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'parent_id',
    ];

    // ========================
    // Relations
    // ========================

    /** البوست المرتبط بالتعليق */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /** صاحب التعليق */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** التعليق الأب (في حالة الرد) */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /** الردود على التعليق */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // ========================
    // Scopes
    // ========================

    /** التعليقات الجذر فقط */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}