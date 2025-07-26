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
            $table->boolean('is_printed')->default(false)->after('status'); // Status sudah dicetak
            $table->timestamp('printed_at')->nullable()->after('is_printed'); // Tanggal dicetak
            $table->unsignedBigInteger('printed_by')->nullable()->after('printed_at'); // Dicetak oleh siapa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['is_printed', 'printed_at', 'printed_by']);
        });
    }
};
