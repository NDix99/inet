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
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('base_price', 12, 2)->after('price')->default(0); // Harga dasar sebelum PPN
            $table->decimal('tax_amount', 12, 2)->after('base_price')->default(0); // Jumlah PPN
        });

        // Update data yang sudah ada
        $ppnRate = 0.11; // 11% PPN
        
        // Mengambil semua paket
        $packages = DB::table('packages')->get();
        
        foreach ($packages as $package) {
            // Menghitung harga dasar (sebelum PPN)
            $basePrice = round($package->price / (1 + $ppnRate), 2);
            
            // Menghitung jumlah PPN
            $taxAmount = round($basePrice * $ppnRate, 2);
            
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
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('base_price');
            $table->dropColumn('tax_amount');
        });
    }
};
