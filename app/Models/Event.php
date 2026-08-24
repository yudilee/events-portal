<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_unit_id',
        'title',
        'slug',
        'subtitle',
        'description',
        'content_html',
        'post_event_summary',
        'event_type',
        'visibility',
        'date',
        'start_time',
        'end_time',
        'timezone',
        'venue_name',
        'venue_address',
        'venue_map_url',
        'dress_code',
        'rsvp_contact',
        'rsvp_phone',
        'max_capacity',
        'registration_deadline',
        'hero_image',
        'status',
        'reschedule_notice',
        'original_date',
        'is_date_tba',
        'is_registration_enabled',
        'is_guestbook_enabled',
        'is_gallery_enabled',
        'is_post_event_enabled',
        'post_event_published_at',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'original_date' => 'date:Y-m-d',
        'is_date_tba' => 'boolean',
        'registration_deadline' => 'datetime:Y-m-d H:i',
        'post_event_published_at' => 'datetime:Y-m-d H:i',
        'is_registration_enabled' => 'boolean',
        'is_guestbook_enabled' => 'boolean',
        'is_gallery_enabled' => 'boolean',
        'is_post_event_enabled' => 'boolean',
        'max_capacity' => 'integer',
    ];

    protected $appends = [
        'formatted_date',
        'formatted_original_date',
        'is_rescheduled',
    ];

    public function getFormattedDateAttribute(): string
    {
        return $this->date ? \Carbon\Carbon::parse($this->date)->format('D, d M Y') : '';
    }

    public function getFormattedOriginalDateAttribute(): string
    {
        return $this->original_date ? \Carbon\Carbon::parse($this->original_date)->format('D, d M Y') : '';
    }

    public function getIsRescheduledAttribute(): bool
    {
        return $this->status === 'rescheduled' || !empty($this->reschedule_notice);
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agendas(): HasMany
    {
        return $this->hasMany(EventAgenda::class)->orderBy('sort_order', 'asc')->orderBy('time_start', 'asc');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function guestBookEntries(): HasMany
    {
        return $this->hasMany(GuestBookEntry::class)->latest();
    }

    public function pressReleases(): HasMany
    {
        return $this->hasMany(PressRelease::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(EventMedia::class)->orderBy('sort_order', 'asc');
    }

    public function galleryMedia(): HasMany
    {
        return $this->hasMany(EventMedia::class)->where('collection', 'gallery')->orderBy('sort_order', 'asc');
    }

    public function postEventMedia(): HasMany
    {
        return $this->hasMany(EventMedia::class)->where('collection', 'post_event')->orderBy('sort_order', 'asc');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(EventComment::class)->where('is_approved', true)->latest();
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(EventComment::class)->latest();
    }

    public function highlightedComments(): HasMany
    {
        return $this->hasMany(EventComment::class)->where('is_approved', true)->where('is_highlighted', true)->latest();
    }

    public function heroSlides(): HasMany
    {
        return $this->hasMany(HeroSlide::class)->orderBy('sort_order', 'asc');
    }

    public function doorprizes(): HasMany
    {
        return $this->hasMany(Doorprize::class)->orderBy('sort_order', 'asc');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereIn('status', ['published', 'rescheduled', 'ongoing']);
    }

    public function scopeRescheduled(Builder $query): Builder
    {
        return $query->where('status', 'rescheduled')->orWhereNotNull('reschedule_notice');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('date', '>=', now()->toDateString())->orderBy('date', 'asc');
    }

    public function getConfirmedRegistrationsCountAttribute(): int
    {
        return $this->registrations()->whereIn('status', ['confirmed', 'attended'])->sum('num_attendees');
    }

    public function getIsFullAttribute(): bool
    {
        return $this->confirmed_registrations_count >= $this->max_capacity;
    }
}
