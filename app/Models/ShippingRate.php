<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'destination',
        'rate_per_kg',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rate_per_kg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scopes
     */
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Helper methods
     */
    
    public static function getRateByDestination($destination)
    {
        $rate = static::where('destination', $destination)
                     ->where('is_active', true)
                     ->first();
        
        return $rate ? $rate->rate_per_kg : 0;
    }

    public static function calculateShipping($destination, $weightKg)
    {
        $ratePerKg = static::getRateByDestination($destination);
        return $ratePerKg * $weightKg;
    }

    public static function getActiveDestinations()
    {
        return static::where('is_active', true)
                    ->pluck('destination', 'id');
    }
}
