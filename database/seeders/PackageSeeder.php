<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Paket Internet
        Package::create([
            'name' => 'Paket Internet 10 Mbps',
            'type' => 'internet',
            'price' => 250000,
            'description' => 'Paket internet dengan kecepatan 10 Mbps',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 20 Mbps',
            'type' => 'internet',
            'price' => 350000,
            'description' => 'Paket internet dengan kecepatan 20 Mbps',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 50 Mbps',
            'type' => 'internet',
            'price' => 500000,
            'description' => 'Paket internet dengan kecepatan 50 Mbps',
            'is_active' => true,
        ]);

        // Paket Metro
        Package::create([
            'name' => 'Paket Metro 10 Mbps',
            'type' => 'metro',
            'price' => 500000,
            'description' => 'Paket metro dengan kecepatan 10 Mbps',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Metro 20 Mbps',
            'type' => 'metro',
            'price' => 750000,
            'description' => 'Paket metro dengan kecepatan 20 Mbps',
            'is_active' => true,
        ]);
    }
} 