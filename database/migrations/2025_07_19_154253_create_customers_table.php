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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama pelanggan
            $table->string('email')->unique(); // Email pelanggan
            $table->string('address'); // Alamat pelanggan
            $table->string('phone'); // Nomor telepon
            $table->decimal('latitude', 10, 7)->nullable(); // Titik koordinat latitude
            $table->decimal('longitude', 10, 7)->nullable(); // Titik koordinat longitude
            $table->date('billing_date'); // Tanggal tagihan
            $table->unsignedBigInteger('package_id'); // Paket yang dipilih
            $table->unsignedBigInteger('created_by'); // Teknisi yang membuat
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
