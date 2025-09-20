<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Appearance extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = [
        'value' => 'array',
    ];

    protected static $cacheTTL = 86400; // 1 ngày

    /**
     * Lấy setting từ cache
     */
    public static function getValue(string $key, $default = [])
    {
        return Cache::rememberForever("appearance_{$key}", function () use ($key, $default) {
            $record = static::where('key', $key)->first();
            return $record ? $record->value : $default;
        });
    }

    /**
     * Cập nhật setting và cache lại
     */
    public static function setValue(string $key, $value)
    {
        $record = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forever("appearance_{$key}", $value);

        return $record;
    }

    /**
     * Khi xóa thì cũng clear cache
     */
    protected static function booted()
    {
        static::updated(function ($setting) {
            Cache::forever("appearance_{$setting->key}", $setting->value);
        });

        static::deleted(function ($setting) {
            Cache::forget("appearance_{$setting->key}");
        });
    }
}
