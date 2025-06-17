<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'is_pinned',
        'is_locked',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    public function latestReply(): HasOne
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Get the image URLs for the topic.
     */
    public function getImageUrlsAttribute(): array
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        return collect($this->images)->map(function ($image) {
            return url('storage/' . $image);
        })->toArray();
    }

    /**
     * Check if topic has images
     */
    public function hasImages(): bool
    {
        return !empty($this->images) && is_array($this->images) && count($this->images) > 0;
    }


    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeNotLocked($query)
    {
        return $query->where('is_locked', false);
    }
}
