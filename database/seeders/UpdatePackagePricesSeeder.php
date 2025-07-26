<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePackagePricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Konstanta PPN
        $ppnRate = 0.11; // 11%
        
        // Data paket yang akan diperbarui atau dibuat
        $packages = [
            [
                'name' => 'Paket Internet 10 Mbps',
                'type' => 'internet',
                'price' => 250000,
                'description' => 'Paket internet dengan kecepatan 10 Mbps',
            ],
            [
                'name' => 'Paket Internet 20 Mbps',
                'type' => 'internet',
                'price' => 350000,
                'description' => 'Paket internet dengan kecepatan 20 Mbps',
            ],
            [
                'name' => 'Paket Internet 50 Mbps',
                'type' => 'internet',
                'price' => 500000,
                'description' => 'Paket internet dengan kecepatan 50 Mbps',
            ],
            [
                'name' => 'Paket Metro 10 Mbps',
                'type' => 'metro',
                'price' => 500000,
                'description' => 'Paket metro dengan kecepatan 10 Mbps',
            ],
            [
                'name' => 'Paket Metro 20 Mbps',
                'type' => 'metro',
                'price' => 750000,
                'description' => 'Paket metro dengan kecepatan 20 Mbps',
            ],
        ];
        
        // Perbarui atau buat paket
        foreach ($packages as $packageData) {
            $package = Package::where('name', $packageData['name'])->first();
            
            // Hitung harga dasar dan PPN dari total harga
            $totalPrice = $packageData['price'];
            $basePrice = round($totalPrice / (1 + $ppnRate), 2);
            $taxAmount = round($totalPrice - $basePrice, 2);
            
            if ($package) {
                // Perbarui paket yang sudah ada
                $package->update([
                    'price' => $totalPrice,
                    'base_price' => $basePrice,
                    'tax_amount' => $taxAmount,
                    'type' => $packageData['type'],
                    'description' => $packageData['description'],
                    'is_active' => true,
                ]);
            } else {
                // Buat paket baru
                Package::create([
                    'name' => $packageData['name'],
                    'type' => $packageData['type'],
                    'price' => $totalPrice,
                    'base_price' => $basePrice,
                    'tax_amount' => $taxAmount,
                    'description' => $packageData['description'],
                    'is_active' => true,
                ]);
            }
        }
        
        $this->command->info('Paket berhasil diperbarui dengan perhitungan harga yang benar.');
    }
}
