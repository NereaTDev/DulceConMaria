<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Recipe extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'ingredients',
        'description',
        'image_path',
        'is_public',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'is_public'   => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) return null;
        // Cloudinary URLs come as full https:// URLs
        if (str_starts_with($this->image_path, 'http')) return $this->image_path;
        // Legacy local storage path
        return Storage::url($this->image_path);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class)->withTimestamps();
    }
}
