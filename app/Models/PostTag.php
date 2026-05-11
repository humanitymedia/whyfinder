<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class PostTag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(function (PostTag $tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
                $original = $tag->slug;
                $count = 1;
                while (static::where('slug', $tag->slug)->exists()) {
                    $tag->slug = $original . '-' . $count++;
                }
            }
        });
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_post_tag');
    }
}
