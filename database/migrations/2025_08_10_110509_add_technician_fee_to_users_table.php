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
        // 1. Tambahkan kolom fee teknisi ke tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('technician_fee_percentage', 5, 2)->default(0)->after('is_active');
            $table->decimal('technician_fee_amount', 12, 2)->default(0)->after('technician_fee_percentage');
        });

        // 2. Hapus kolom fee teknisi dari tabel packages
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['technician_fee_percentage', 'technician_fee_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Kembalikan kolom fee teknisi ke tabel packages
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('technician_fee_percentage', 5, 2)->default(0)->after('price');
            $table->decimal('technician_fee_amount', 12, 2)->default(0)->after('technician_fee_percentage');
        });

        // 2. Hapus kolom fee teknisi dari tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['technician_fee_percentage', 'technician_fee_amount']);
        });
    }
};