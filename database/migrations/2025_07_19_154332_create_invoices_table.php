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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Nomor invoice
            $table->unsignedBigInteger('customer_id'); // ID pelanggan
            $table->unsignedBigInteger('package_id'); // ID paket
            $table->date('invoice_date'); // Tanggal invoice
            $table->date('due_date'); // Tanggal jatuh tempo
            $table->decimal('amount', 12, 2); // Jumlah tagihan
            $table->decimal('tax_percentage', 5, 2)->default(0); // Persentase pajak
            $table->decimal('tax_amount', 12, 2)->default(0); // Jumlah pajak
            $table->decimal('technician_fee_percentage', 5, 2)->default(0); // Persentase fee teknisi
           // $table->decimal('technician_fee_amount', 12, 2)->default(0); // Jumlah fee teknisi
            $table->decimal('total_amount', 12, 2); // Total tagihan
            $table->enum('status', ['paid', 'unpaid', 'overdue', 'cancelled'])->default('unpaid'); // Status invoice
            $table->text('notes')->nullable(); // Catatan
            $table->unsignedBigInteger('created_by'); // Dibuat oleh
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
