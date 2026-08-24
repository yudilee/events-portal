<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'guest_name',
        'guest_company',
        'guest_role',
        'message',
        'photo_path',
        'type',
        'likes_count',
        'is_approved',
        'is_highlighted',
    ];

    protected $casts = [
        'likes_count' => 'integer',
        'is_approved' => 'boolean',
        'is_highlighted' => 'boolean',
    ];

    protected $appends = ['photo_url'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }

        if (str_starts_with($this->photo_path, 'http://') || str_starts_with($this->photo_path, 'https://')) {
            return $this->photo_path;
        }

        return asset('storage/' . $this->photo_path);
    }
}
