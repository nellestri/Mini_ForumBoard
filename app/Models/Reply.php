<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'topic_id',
        'user_id',
        'images'
    ];
    protected $casts = [
        'images' => 'array',
    ];


    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     /**
     * Get the image URLs for the reply.
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
     * Check if reply has images
     */
    public function hasImages(): bool
    {
        return !empty($this->images) && is_array($this->images) && count($this->images) > 0;
    }
}


