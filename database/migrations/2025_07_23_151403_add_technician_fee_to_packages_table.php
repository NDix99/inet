<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('technician_fee_percentage', 5, 2)->default(0)->after('price'); // Persentase fee teknisi
            $table->decimal('technician_fee_amount', 12, 2)->default(0)->after('technician_fee_percentage'); // Jumlah fee teknisi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('technician_fee_percentage');
            $table->dropColumn('technician_fee_amount');
        });
    }
};
