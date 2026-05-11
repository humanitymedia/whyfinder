<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ForumThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'lesson_id',
        'title',
        'slug',
        'body',
        'is_pinned',
        'is_locked',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ForumThread $thread) {
            if (empty($thread->slug)) {
                $thread->slug = Str::slug($thread->title);
                $original = $thread->slug;
                $count = 1;
                while (static::where('slug', $thread->slug)
                    ->where('course_id', $thread->course_id)
                    ->exists()
                ) {
                    $thread->slug = $original . '-' . $count++;
                }
            }
        });
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'thread_id');
    }

    public function approvedReplies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'thread_id')->where('is_approved', true);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeNotLocked($query)
    {
        return $query->where('is_locked', false);
    }

    public function getReplyCountAttribute(): int
    {
        return $this->approvedReplies()->count();
    }

    public function getStatusBadgeColorAttribute(): string
    {
        if ($this->is_locked) {
            return 'bg-red-100 text-red-700';
        }
        if ($this->is_pinned) {
            return 'bg-amber-100 text-amber-700';
        }

        return 'bg-green-100 text-green-700';
    }
}
