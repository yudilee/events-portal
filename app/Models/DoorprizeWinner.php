<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoorprizeWinner extends Model
{
    use HasFactory;

    protected $fillable = [
        'doorprize_id',
        'guest_book_entry_id',
        'won_at',
    ];

    protected $casts = [
        'won_at' => 'datetime',
    ];

    public function doorprize(): BelongsTo
    {
        return $this->belongsTo(Doorprize::class);
    }

    public function guestBookEntry(): BelongsTo
    {
        return $this->belongsTo(GuestBookEntry::class, 'guest_book_entry_id');
    }
}
