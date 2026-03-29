<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ========================
    // Relations
    // ========================

    /** بروفايل الطالب */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    /** الدوائر اللي الطالب مشترك فيها */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_student')
                    ->withPivot(['enrolled_at', 'is_active'])
                    ->withTimestamps();
    }

    /** السجلات اليومية للطالب */
    public function dailyLogs(): HasMany
    {
        return $this->hasMany(DailyLog::class, 'student_id');
    }

    /** السجلات اليومية اللي سجّلها (كمدرس) */
    public function recordedLogs(): HasMany
    {
        return $this->hasMany(DailyLog::class, 'recorded_by');
    }

    /** البوستات اللي كتبها */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    /** التعليقات */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /** تقييمات الطالب */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'student_id');
    }
}