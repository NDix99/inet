<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update data yang sudah ada
        $ppnRate = 0.11; // 11% PPN
        
        // Mengambil semua paket
        $packages = DB::table('packages')->get();
        
        foreach ($packages as $package) {
            // Harga tetap sama dengan price
            // Menghitung harga dasar (sebelum PPN)
            $basePrice = round($package->price / (1 + $ppnRate), 2);
            
            // Menghitung jumlah PPN
            $taxAmount = round($package->price - $basePrice, 2);
            
            // Update record
            DB::table('packages')
                ->where('id', $package->id)
                ->update([
                    'base_price' => $basePrice,
                    'tax_amount' => $taxAmount,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada yang perlu di-rollback karena kita hanya mengupdate data
    }
};
