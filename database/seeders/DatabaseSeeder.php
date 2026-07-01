<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
public function run(): void
{
    // Admin user
    \App\Models\User::firstOrCreate(
        ['email' => 'admin@fixgo.com'],
        [
            'name' => 'Admin',
            'phone' => '0781272193',
            'role' => 'admin',
            'password' => bcrypt('2004'),
        ]
    );

    // Service Categories
    $services = ['Battery Jump Start', 'Brake Repair', 'Fuel Delivery',
                 'Tyre Change', 'Engine Repair', 'Electrical', 'Diagnostics'];
    foreach ($services as $service) {
        \App\Models\ServiceCategory::firstOrCreate(['name' => $service]);
    }

    // Vehicle Categories
    $vehicles = ['Sedan', 'SUV', 'Pickup Truck', 'Minivan', 'Motorcycle', 'Bus'];
    foreach ($vehicles as $vehicle) {
        \App\Models\VehicleCategory::firstOrCreate(['name' => $vehicle]);
    }
}
}
