<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doorprize extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'label',
        'description',
        'quantity',
        'sort_order',
        'winner_guest_book_entry_id',
        'won_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'sort_order' => 'integer',
        'won_at' => 'datetime',
    ];

    protected $appends = [
        'awarded_count',
        'is_fully_awarded',
        'remaining_quantity',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function winners(): HasMany
    {
        return $this->hasMany(DoorprizeWinner::class)->orderBy('created_at', 'asc');
    }

    public function winnerEntries(): BelongsToMany
    {
        return $this->belongsToMany(GuestBookEntry::class, 'doorprize_winners', 'doorprize_id', 'guest_book_entry_id')
            ->withPivot('won_at')
            ->withTimestamps();
    }

    public function winnerEntry(): BelongsTo
    {
        return $this->belongsTo(GuestBookEntry::class, 'winner_guest_book_entry_id');
    }

    public function getAwardedCountAttribute(): int
    {
        if ($this->relationLoaded('winners')) {
            return $this->winners->count();
        }
        return $this->winners()->count();
    }

    public function getIsFullyAwardedAttribute(): bool
    {
        return $this->getAwardedCountAttribute() >= ($this->quantity ?: 1);
    }

    public function getRemainingQuantityAttribute(): int
    {
        return max(0, ($this->quantity ?: 1) - $this->getAwardedCountAttribute());
    }

    public function isWon(): bool
    {
        return $this->getAwardedCountAttribute() > 0;
    }
}
