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
        Schema::create('mitra_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('technician_id');
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->decimal('total_fee', 16, 2)->default(0);
            $table->decimal('total_revenue', 16, 2)->default(0);
            $table->decimal('total_pt_fee', 16, 2)->default(0);
            $table->decimal('total_ppn', 16, 2)->default(0);
            $table->boolean('is_printed')->default(false);
            $table->timestamp('printed_at')->nullable();
            $table->unsignedBigInteger('printed_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_reports');
    }
};
