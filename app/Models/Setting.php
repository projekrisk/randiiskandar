<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Ambil nilai pengaturan berdasarkan key dengan caching.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        try {
            return Cache::remember("setting_{$key}", 86400, function () use ($key, $default) {
                $setting = static::where('key', $key)->first();

                return $setting !== null ? $setting->value : $default;
            });
        } catch (\Throwable) {
            return $default;
        }
    }

    /**
     * Simpan atau perbarui nilai pengaturan dan bersihkan cache.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting_{$key}");
    }
}
