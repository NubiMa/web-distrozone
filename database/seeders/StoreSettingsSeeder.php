<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'qris_image',
                'value' => 'images/payment/qris-distrozone.png',
                'type' => 'text',
                'description' => 'Path to QRIS QR code image for payment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_name',
                'value' => 'DistroZone',
                'type' => 'text',
                'description' => 'Store name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'store_address',
                'value' => 'Jln. Raya Pegangsaan Timur No.29H, Kelapa Gading, Jakarta',
                'type' => 'text',
                'description' => 'Store physical address',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'bank_name',
                'value' => 'Bank BCA',
                'type' => 'text',
                'description' => 'Bank name for manual transfer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'bank_account_number',
                'value' => '1234567890',
                'type' => 'text',
                'description' => 'Bank account number',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'bank_account_holder',
                'value' => 'DistroZone Inc.',
                'type' => 'text',
                'description' => 'Bank account holder name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('store_settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
