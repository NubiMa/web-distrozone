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
        // Get store timezone
        $timezone = static::get('store_timezone', 'Asia/Jakarta');
        
        // Get the day-specific hours configuration
        $onlineHours = static::get('online_hours', null);
        
        // Fallback to old simple open/close time if new structure doesn't exist
        if (!$onlineHours || !is_array($onlineHours)) {
            $openTime = static::get('online_open_time', '10:00');
            $closeTime = static::get('online_close_time', '17:00');
            $currentTime = now($timezone)->format('H:i');
            return $currentTime >= $openTime && $currentTime <= $closeTime;
        }
        
        // Get current day in store's timezone
        $currentDay = now($timezone)->format('l');
        
        // Check if today exists in schedule
        if (!isset($onlineHours[$currentDay])) {
            return false;
        }
        
        $todaySchedule = $onlineHours[$currentDay];
        
        // Check if store is closed today (handle both boolean and string "1"/1)
        $isClosed = isset($todaySchedule['closed']) && 
                    ($todaySchedule['closed'] === true || 
                     $todaySchedule['closed'] === 1 || 
                     $todaySchedule['closed'] === '1');
        
        if ($isClosed) {
            return false;
        }
        
        // Check current time against schedule in store's timezone
        $currentTime = now($timezone)->format('H:i');
        $openTime = $todaySchedule['start'] ?? '09:00';
        $closeTime = $todaySchedule['end'] ?? '21:00';
        
        return $currentTime >= $openTime && $currentTime <= $closeTime;
    }
    
    /**
     * Get human-readable store status message
     */
    public static function getStoreStatusMessage()
    {
        if (static::isOnlineStoreOpen()) {
            return 'Toko sedang buka';
        }
        
        $timezone = static::get('store_timezone', 'Asia/Jakarta');
        $onlineHours = static::get('online_hours', null);
        $currentDay = now($timezone)->format('l');
        
        if ($onlineHours && isset($onlineHours[$currentDay])) {
            $todaySchedule = $onlineHours[$currentDay];
            
            if (isset($todaySchedule['closed']) && 
                ($todaySchedule['closed'] === true || 
                 $todaySchedule['closed'] === 1 || 
                 $todaySchedule['closed'] === '1')) {
                return 'Toko tutup hari ini';
            }
            
            $openTime = $todaySchedule['start'] ?? '09:00';
            $closeTime = $todaySchedule['end'] ?? '21:00';
            
            return "Toko sedang tutup. Jam operasional hari ini: {$openTime} - {$closeTime}";
        }
        
        return 'Toko sedang tutup';
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
