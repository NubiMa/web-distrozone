<?php

namespace App\Services;

use App\Models\ShippingRate;

class ShippingService
{
    /**
     * Calculate weight in Kg based on quantity
     * Formula: 1 Kg = max 3 shirts, less than 3 still counted as 1 Kg
     * 
     * @param int $quantity
     * @return int
     */
    public function calculateWeight(int $quantity): int
    {
        // Ceil function ensures: 1-3 items = 1kg, 4-6 items = 2kg, etc.
        return (int) ceil($quantity / 3);
    }

    /**
     * Calculate shipping cost based on destination and quantity
     * 
     * @param string $destination
     * @param int $quantity
     * @return array
     */
    public function calculateShippingCost(string $destination, int $quantity): array
    {
        $weightKg = $this->calculateWeight($quantity);
        $ratePerKg = ShippingRate::getRateByDestination($destination);
        
        if ($ratePerKg === 0) {
            return [
                'success' => false,
                'message' => 'Destination not available or invalid',
                'weight_kg' => $weightKg,
                'rate_per_kg' => 0,
                'shipping_cost' => 0,
            ];
        }

        $shippingCost = $ratePerKg * $weightKg;

        return [
            'success' => true,
            'message' => 'Shipping cost calculated successfully',
            'destination' => $destination,
            'quantity' => $quantity,
            'weight_kg' => $weightKg,
            'rate_per_kg' => $ratePerKg,
            'shipping_cost' => $shippingCost,
        ];
    }

    /**
     * Get all available shipping destinations with rates
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableDestinations()
    {
        return ShippingRate::active()
            ->orderBy('destination')
            ->get()
            ->map(function ($rate) {
                return [
                    'id' => $rate->id,
                    'destination' => $rate->destination,
                    'rate_per_kg' => $rate->rate_per_kg,
                    'formatted_rate' => 'Rp ' . number_format($rate->rate_per_kg, 0, ',', '.'),
                ];
            });
    }

    /**
     * Validate if shipping to destination is available
     * 
     * @param string $destination
     * @return bool
     */
    public function isDestinationAvailable(string $destination): bool
    {
        $rate = ShippingRate::where('destination', $destination)
                           ->where('is_active', true)
                           ->first();
        
        return $rate !== null;
    }

    /**
     * Get shipping quote for multiple items
     * 
     * @param array $items [['quantity' => 2], ['quantity' => 3], ...]
     * @param string $destination
     * @return array
     */
    public function getShippingQuote(array $items, string $destination): array
    {
        $totalQuantity = collect($items)->sum('quantity');
        
        return $this->calculateShippingCost($destination, $totalQuantity);
    }
}
