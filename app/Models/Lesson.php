<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'slug',
        'type',
        'content',
        'video_url',
        'video_duration',
        'downloadable_file_path',
        'sort_order',
        'is_free_preview',
    ];

    protected function casts(): array
    {
        return [
            'is_free_preview' => 'boolean',
            'video_duration' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Lesson $lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }
        });
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function course(): BelongsTo
    {
        return $this->section->course();
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function forumThreads(): HasMany
    {
        return $this->hasMany(ForumThread::class);
    }

    public function getFormattedDurationAttribute(): string
    {
        if (! $this->video_duration) {
            return '0:00';
        }

        $minutes = floor($this->video_duration / 60);
        $seconds = $this->video_duration % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
