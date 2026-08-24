<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAgenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'time_start',
        'time_end',
        'title',
        'description',
        'speaker_name',
        'speaker_role',
        'sort_order',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
