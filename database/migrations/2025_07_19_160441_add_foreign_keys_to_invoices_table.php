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
        Schema::table('invoices', function (Blueprint $table) {
            // Menambahkan foreign key ke tabel customers
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            
            // Menambahkan foreign key ke tabel packages
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            
            // Menambahkan foreign key ke tabel users
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Menghapus foreign keys
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['package_id']);
            $table->dropForeign(['created_by']);
        });
    }
};
