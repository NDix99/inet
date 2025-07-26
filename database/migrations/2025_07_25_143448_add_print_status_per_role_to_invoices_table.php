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
            $table->boolean('is_printed_admin')->default(false)->after('is_printed');
            $table->timestamp('printed_at_admin')->nullable()->after('is_printed_admin');
            $table->unsignedBigInteger('printed_by_admin')->nullable()->after('printed_at_admin');
            $table->boolean('is_printed_technician')->default(false)->after('printed_by_admin');
            $table->timestamp('printed_at_technician')->nullable()->after('is_printed_technician');
            $table->unsignedBigInteger('printed_by_technician')->nullable()->after('printed_at_technician');
            $table->boolean('is_printed_superadmin')->default(false)->after('printed_by_technician');
            $table->timestamp('printed_at_superadmin')->nullable()->after('is_printed_superadmin');
            $table->unsignedBigInteger('printed_by_superadmin')->nullable()->after('printed_at_superadmin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'is_printed_admin', 'printed_at_admin', 'printed_by_admin',
                'is_printed_technician', 'printed_at_technician', 'printed_by_technician',
                'is_printed_superadmin', 'printed_at_superadmin', 'printed_by_superadmin',
            ]);
        });
    }
};
