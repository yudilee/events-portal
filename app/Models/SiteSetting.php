<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    /**
     * Get a setting by key with caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("site_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            if (! $setting) {
                return $default;
            }

            if ($setting->type === 'json') {
                return json_decode($setting->value, true) ?: $default;
            }

            if ($setting->type === 'boolean') {
                return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            }

            return $setting->value ?? $default;
        });
    }

    /**
     * Set a setting value and clear cache.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'text'): static
    {
        $rawValue = $value;
        if ($type === 'json' && is_array($value)) {
            $rawValue = json_encode($value);
        } elseif ($type === 'boolean') {
            $rawValue = $value ? '1' : '0';
        }

        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $rawValue,
                'group' => $group,
                'type' => $type,
            ]
        );

        Cache::forget("site_setting_{$key}");

        return $setting;
    }
}
