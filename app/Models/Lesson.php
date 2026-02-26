<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'order',
        'video_url',
        'summary',
        'is_free_preview',
    ];

    protected $casts = [
        'is_free_preview' => 'boolean',
    ];

    /**
     * URL normalizada para incrustar el vídeo (YouTube u otros proveedores similares).
     * Permite pegar URLs tipo watch?v=..., youtu.be/... o embed directamente.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        $url = $this->video_url;
        if (! $url) {
            return null;
        }

        // Si ya es una URL de embed, la devolvemos tal cual.
        if (str_contains($url, 'youtube.com/embed') || str_contains($url, 'player.vimeo.com')) {
            return $url;
        }

        // YouTube: https://www.youtube.com/watch?v=ID
        if (preg_match('~youtube\.com/watch\?v=([^&]+)~', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // YouTube corto: https://youtu.be/ID
        if (preg_match('~youtu\.be/([^?&]+)~', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // Fallback: devolvemos la URL tal cual por si ya es válida para iframe.
        return $url;
    }

    /**
     * Miniatura del vídeo (YouTube) si se puede deducir.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        $url = $this->video_url;
        if (! $url) {
            return null;
        }

        // Extraemos el ID de YouTube de las variantes más comunes.
        if (preg_match('~youtube\.com/watch\?v=([^&]+)~', $url, $matches)) {
            $id = $matches[1];
        } elseif (preg_match('~youtu\.be/([^?&]+)~', $url, $matches)) {
            $id = $matches[1];
        } elseif (preg_match('~youtube\.com/embed/([^?&]+)~', $url, $matches)) {
            $id = $matches[1];
        } else {
            return null;
        }

        return 'https://img.youtube.com/vi/' . $id . '/hqdefault.jpg';
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class)->withTimestamps();
    }
}
