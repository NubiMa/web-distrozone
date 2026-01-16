<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates default admin and sample kasir users
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin DistroZone',
            'email' => 'admin@distrozone.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jln. Raya Pegangsaan Timur No.29H, Kelapa Gading, Jakarta',
            'is_active' => true,
        ]);

        $this->command->info('Admin created: admin@distrozone.com / admin123');

        // Create Sample Kasir Users
        $kasir1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@distrozone.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
            'phone' => '081234567891',
            'address' => 'Jakarta',
            'is_active' => true,
        ]);

        // Create employee record for kasir1
        Employee::create([
            'user_id' => $kasir1->id,
            'nik' => '3175012345670001',
            'name' => 'Budi Santoso',
            'address' => 'Jakarta',
            'phone' => '081234567891',
        ]);

        $kasir2 = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@distrozone.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
            'phone' => '081234567892',
            'address' => 'Jakarta',
            'is_active' => true,
        ]);

        // Create employee record for kasir2
        Employee::create([
            'user_id' => $kasir2->id,
            'nik' => '3175012345670002',
            'name' => 'Siti Rahayu',
            'address' => 'Jakarta',
            'phone' => '081234567892',
        ]);

        $this->command->info('Kasir users created: budi@distrozone.com / kasir123, siti@distrozone.com / kasir123');

        // Create Sample Customer
        $customer = User::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'phone' => '081234567893',
            'address' => 'Jakarta Selatan',
            'is_active' => true,
        ]);

        $this->command->info('Customer created: customer@test.com / customer123');
        $this->command->info('All users seeded successfully!');
    }
}
