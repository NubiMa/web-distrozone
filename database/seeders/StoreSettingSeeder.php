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
            
            // Online store hours (per-day schedule)
            [
                'key' => 'online_hours',
                'value' => json_encode([
                    'Monday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
                    'Tuesday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
                    'Wednesday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
                    'Thursday' => ['start' => '09:00', 'end' => '21:00', 'closed' => false],
                    'Friday' => ['start' => '09:00', 'end' => '22:00', 'closed' => false],
                    'Saturday' => ['start' => '10:00', 'end' => '22:00', 'closed' => false],
                    'Sunday' => ['start' => '10:00', 'end' => '21:00', 'closed' => false],
                ]),
                'type' => 'json',
                'description' => 'Online store hours for each day of the week',
            ],
            
            // Store contact information
            [
                'key' => 'store_email',
                'value' => 'help@distrozone.com',
                'type' => 'text',
                'description' => 'Store support email',
            ],
            [
                'key' => 'store_phone',
                'value' => '+62 21 5555 0199',
                'type' => 'text',
                'description' => 'Store phone number',
            ],
            [
                'key' => 'store_currency',
                'value' => 'IDR',
                'type' => 'text',
                'description' => 'Store currency',
            ],
            [
                'key' => 'store_timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'text',
                'description' => 'Store timezone',
            ],
        ];

        foreach ($settings as $setting) {
            StoreSetting::create($setting);
        }

        $this->command->info('Store settings seeded successfully!');
    }
}
