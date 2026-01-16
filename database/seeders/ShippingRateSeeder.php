<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Based on brief requirements - tarif ongkos kirim per kg
     */
    public function run(): void
    {
        $shippingRates = [
            ['destination' => 'Jakarta', 'rate_per_kg' => 24000],
            ['destination' => 'Depok', 'rate_per_kg' => 24000],
            ['destination' => 'Bekasi', 'rate_per_kg' => 25000],
            ['destination' => 'Tangerang', 'rate_per_kg' => 25000],
            ['destination' => 'Bogor', 'rate_per_kg' => 27000],
            ['destination' => 'Jawa Barat', 'rate_per_kg' => 31000],
            ['destination' => 'Jawa Tengah', 'rate_per_kg' => 39000],
            ['destination' => 'Jawa Timur', 'rate_per_kg' => 47000],
        ];

        foreach ($shippingRates as $rate) {
            ShippingRate::create([
                'destination' => $rate['destination'],
                'rate_per_kg' => $rate['rate_per_kg'],
                'is_active' => true,
            ]);
        }

        $this->command->info('Shipping rates seeded successfully!');
    }
}
