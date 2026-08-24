<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'parent_registration_id',
        'registration_code',
        'full_name',
        'email',
        'phone',
        'company',
        'attendee_type',
        'num_attendees',
        'vehicle_model',
        'license_plate',
        'media_outlet_name',
        'dietary_notes',
        'notes',
        'status',
        'checked_in_at',
        'checked_in_by',
        'qr_code_data',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'num_attendees' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($registration) {
            if (empty($registration->registration_code)) {
                $registration->registration_code = 'HRM-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);
            }
            if (empty($registration->qr_code_data)) {
                $registration->qr_code_data = $registration->registration_code;
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checkedInByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function parentRegistration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'parent_registration_id');
    }

    public function companions(): HasMany
    {
        return $this->hasMany(Registration::class, 'parent_registration_id');
    }

    public function guestBookEntry(): HasOne
    {
        return $this->hasOne(GuestBookEntry::class);
    }

    public function isCheckedIn(): bool
    {
        return $this->checked_in_at !== null;
    }
}
