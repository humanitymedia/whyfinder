<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    /**
     * Get a setting value by key with type casting.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = Cache::remember("setting.{$key}", 3600, function () use ($key) {
            return static::where('key', $key)->first();
        });

        if (! $setting || $setting->value === null || $setting->value === '') {
            return $default;
        }

        return match ($setting->type) {
            'integer' => (int) $setting->value,
            'float' => (float) $setting->value,
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'encrypted' => self::decryptValue($setting->value),
            default => $setting->value,
        };
    }

    /**
     * Set a setting value (upsert).
     */
    public static function set(string $key, mixed $value, ?string $type = null, ?string $group = null): void
    {
        $setting = static::where('key', $key)->first();

        $storeValue = $value;
        $storeType = $type ?? ($setting?->type ?? 'string');
        $storeGroup = $group ?? ($setting?->group ?? 'general');

        if ($storeType === 'encrypted' && $storeValue !== null && $storeValue !== '') {
            $storeValue = Crypt::encryptString($storeValue);
        }

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $storeValue, 'type' => $storeType, 'group' => $storeGroup],
        );

        Cache::forget("setting.{$key}");
    }

    private static function decryptValue(string $value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception) {
            return '';
        }
    }
}
