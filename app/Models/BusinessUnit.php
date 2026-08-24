<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'subtitle',
        'description',
        'icon',
        'badge_color',
        'sort_order',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('date', 'asc');
    }

    public function pressReleases(): HasMany
    {
        return $this->hasMany(PressRelease::class);
    }
}
