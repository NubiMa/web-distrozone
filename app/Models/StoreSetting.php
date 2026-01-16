<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Helper methods to get/set settings
     */
    
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    public static function set($key, $value, $type = 'text', $description = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'description' => $description,
            ]
        );

        return $setting;
    }

    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($value, true);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            default:
                return $value;
        }
    }

    /**
     * Operational hours helpers
     */
    
    public static function isOnlineStoreOpen()
    {
        $openTime = static::get('online_open_time', '10:00');
        $closeTime = static::get('online_close_time', '17:00');
        $currentTime = now()->format('H:i');

        return $currentTime >= $openTime && $currentTime <= $closeTime;
    }

    public static function isOfflineStoreOpen()
    {
        $openTime = static::get('offline_open_time', '10:00');
        $closeTime = static::get('offline_close_time', '20:00');
        $closedDay = static::get('offline_closed_day', 'Monday');
        $currentTime = now()->format('H:i');
        $currentDay = now()->format('l');

        if ($currentDay === $closedDay) {
            return false;
        }

        return $currentTime >= $openTime && $currentTime <= $closeTime;
    }
}
