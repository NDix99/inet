<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan data paket
        $packages = Package::all();
        if ($packages->isEmpty()) {
            return;
        }

        // Mendapatkan teknisi untuk created_by
        $technician = User::whereHas('role', function($query) {
            $query->where('name', 'technician');
        })->first();

        if (!$technician) {
            return;
        }

        // Membuat data pelanggan
        $customers = [
            [
                'name' => 'PT Maju Jaya',
                'email' => 'info@majujaya.com',
                'address' => 'Jl. Raya Maju No. 123, Jakarta',
                'phone' => '021-5551234',
                'latitude' => -6.175110,
                'longitude' => 106.865036,
                'billing_date' => now(),
                'package_id' => $packages->where('name', 'Paket Internet 20 Mbps')->first()->id ?? $packages->first()->id,
                'created_by' => $technician->id,
                'is_active' => true,
            ],
            [
                'name' => 'CV Sejahtera',
                'email' => 'contact@sejahtera.com',
                'address' => 'Jl. Sejahtera No. 45, Jakarta',
                'phone' => '021-5552345',
                'latitude' => -6.170110,
                'longitude' => 106.855036,
                'billing_date' => now(),
                'package_id' => $packages->where('name', 'Paket Internet 50 Mbps')->first()->id ?? $packages->first()->id,
                'created_by' => $technician->id,
                'is_active' => true,
            ],
            [
                'name' => 'PT Metro Jaya',
                'email' => 'support@metrojaya.com',
                'address' => 'Jl. Metro No. 78, Jakarta',
                'phone' => '021-5553456',
                'latitude' => -6.165110,
                'longitude' => 106.845036,
                'billing_date' => now(),
                'package_id' => $packages->where('name', 'Paket Metro 10 Mbps')->first()->id ?? $packages->first()->id,
                'created_by' => $technician->id,
                'is_active' => true,
            ],
            [
                'name' => 'Toko Bahagia',
                'email' => 'toko@bahagia.com',
                'address' => 'Jl. Bahagia No. 12, Jakarta',
                'phone' => '021-5554567',
                'latitude' => -6.160110,
                'longitude' => 106.835036,
                'billing_date' => now(),
                'package_id' => $packages->where('name', 'Paket Internet 10 Mbps')->first()->id ?? $packages->first()->id,
                'created_by' => $technician->id,
                'is_active' => true,
            ],
            [
                'name' => 'Warnet Cepat',
                'email' => 'warnet@cepat.com',
                'address' => 'Jl. Cepat No. 56, Jakarta',
                'phone' => '021-5555678',
                'latitude' => -6.155110,
                'longitude' => 106.825036,
                'billing_date' => now(),
                'package_id' => $packages->where('name', 'Paket Metro 20 Mbps')->first()->id ?? $packages->first()->id,
                'created_by' => $technician->id,
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }
    }
} 