<?php

namespace Database\Seeders;

use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class StoreSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Based on brief requirements - jam operasional
     */
    public function run(): void
    {
        $settings = [
            // Offline store settings
            [
                'key' => 'offline_open_time',
                'value' => '10:00',
                'type' => 'time',
                'description' => 'Jam buka toko offline',
            ],
            [
                'key' => 'offline_close_time',
                'value' => '20:00',
                'type' => 'time',
                'description' => 'Jam tutup toko offline',
            ],
            [
                'key' => 'offline_closed_day',
                'value' => 'Monday',
                'type' => 'text',
                'description' => 'Hari libur toko offline (bahasa Inggris)',
            ],

            // Online store settings
            [
                'key' => 'online_open_time',
                'value' => '10:00',
                'type' => 'time',
                'description' => 'Jam buka toko online',
            ],
            [
                'key' => 'online_close_time',
                'value' => '17:00',
                'type' => 'time',
                'description' => 'Jam tutup toko online',
            ],

            // Store information
            [
                'key' => 'store_name',
                'value' => 'DistroZone',
                'type' => 'text',
                'description' => 'Nama toko',
            ],
            [
                'key' => 'store_address',
                'value' => 'Jln. Raya Pegangsaan Timur No.29H, Kelapa Gading, Jakarta',
                'type' => 'text',
                'description' => 'Alamat toko',
            ],
            [
                'key' => 'store_description',
                'value' => 'Menjual berbagai macam kaos distro dengan variasi model, warna, dan ukuran',
                'type' => 'text',
                'description' => 'Deskripsi toko',
            ],

            // Additional settings
            [
                'key' => 'max_shirts_per_kg',
                'value' => '3',
                'type' => 'integer',
                'description' => 'Maksimal kaos per kilogram untuk perhitungan ongkir',
            ],
        ];

        foreach ($settings as $setting) {
            StoreSetting::create($setting);
        }

        $this->command->info('Store settings seeded successfully!');
    }
}
