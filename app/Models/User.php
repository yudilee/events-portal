<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'role',
        'status',
        'member_tier',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOrganizer(): bool
    {
        return in_array($this->role, ['admin', 'event_organizer']);
    }

    public function isMember(): bool
    {
        return in_array($this->role, ['member', 'vip', 'admin']);
    }

    public function isNewsAgency(): bool
    {
        return in_array($this->role, ['news_agency', 'media', 'admin']);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }
}
