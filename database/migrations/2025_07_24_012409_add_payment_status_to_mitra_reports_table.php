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
        Schema::table('mitra_reports', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false)->after('printed_by');
            $table->timestamp('payment_date')->nullable()->after('is_paid');
            $table->string('payment_notes')->nullable()->after('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mitra_reports', function (Blueprint $table) {
            $table->dropColumn(['is_paid', 'payment_date', 'payment_notes']);
        });
    }
};
