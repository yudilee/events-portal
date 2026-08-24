<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GuestBookEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'registration_id',
        'guest_name',
        'company',
        'message',
        'photo_path',
        'is_approved',
        'is_highlighted',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_highlighted' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function doorprizeWin(): HasOne
    {
        return $this->hasOne(Doorprize::class, 'winner_guest_book_entry_id');
    }
}
