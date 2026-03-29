<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'author_id',
        'title',
        'content',
        'is_global',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_global'    => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    // ========================
    // Relations
    // ========================

    /** صاحب البوست */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /** الدوائر المرتبطة بالبوست */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'post_group')
            ->withTimestamps();
    }

    /** التعليقات على البوست */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /** التعليقات الجذر فقط (بدون ردود) */
    public function rootComments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    // ========================
    // Scopes
    // ========================

    /** البوستات اللي منشورة */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /** البوستات العامة */
    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }
}
