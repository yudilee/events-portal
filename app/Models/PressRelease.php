<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class PressRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'business_unit_id',
        'title',
        'slug',
        'summary',
        'content',
        'cover_image',
        'is_embargoed',
        'embargo_until',
        'visibility',
        'attachment_zip_path',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'is_embargoed' => 'boolean',
        'embargo_until' => 'datetime',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('visibility', 'public')
                     ->whereNotNull('published_at')
                     ->where(function ($q) {
                         $q->where('is_embargoed', false)
                           ->orWhere('embargo_until', '<=', now());
                     });
    }
}
