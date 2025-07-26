<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateInvoiceCalculationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Konstanta PPN
        $ppnRate = 0.11; // 11%
        
        // Perbarui invoice yang sudah ada
        $invoices = Invoice::all();
        
        foreach ($invoices as $invoice) {
            // Hitung ulang harga dasar dan PPN dari total harga
            $totalAmount = $invoice->total_amount; // Harga total yang sudah termasuk PPN
            $amount = round($totalAmount / (1 + $ppnRate), 2); // Harga dasar
            $taxAmount = round($totalAmount - $amount, 2); // Jumlah PPN
            
            // Update invoice
            $invoice->update([
                'amount' => $amount,
                'tax_amount' => $taxAmount,
            ]);
        }
        
        $this->command->info('Invoice berhasil diperbarui dengan perhitungan harga yang benar.');
        
        // Jika tidak ada invoice, buat invoice baru
        if ($invoices->isEmpty()) {
            $this->createNewInvoices($ppnRate);
        }
    }
    
    /**
     * Membuat invoice baru jika tidak ada invoice yang sudah ada
     */
    private function createNewInvoices($ppnRate)
    {
        // Mendapatkan data pelanggan
        $customers = Customer::all();
        if ($customers->isEmpty()) {
            $this->command->error('Tidak ada pelanggan yang ditemukan.');
            return;
        }

        // Mendapatkan admin untuk created_by
        $admin = User::whereHas('role', function($query) {
            $query->where('name', 'admin');
        })->first();

        if (!$admin) {
            $this->command->error('Tidak ada admin yang ditemukan.');
            return;
        }

        // Untuk setiap pelanggan, buat beberapa invoice
        foreach ($customers as $customer) {
            // Invoice bulan ini (belum lunas)
            $this->createInvoice($customer, $admin, 0, 'unpaid', $ppnRate);
            
            // Invoice bulan lalu (lunas)
            $this->createInvoice($customer, $admin, -1, 'paid', $ppnRate);
            
            // Invoice 2 bulan lalu (lunas)
            $this->createInvoice($customer, $admin, -2, 'paid', $ppnRate);
        }
        
        // Buat beberapa invoice terlambat
        $lateCustomers = $customers->random(min(2, $customers->count()));
        foreach ($lateCustomers as $customer) {
            $this->createInvoice($customer, $admin, -3, 'overdue', $ppnRate);
        }
        
        $this->command->info('Invoice baru berhasil dibuat dengan perhitungan harga yang benar.');
    }
    
    /**
     * Membuat invoice untuk pelanggan dengan harga yang sudah termasuk PPN
     */
    private function createInvoice($customer, $admin, $monthOffset, $status, $ppnRate)
    {
        $invoiceDate = Carbon::now()->addMonths($monthOffset)->startOfMonth();
        $dueDate = clone $invoiceDate;
        $dueDate->addDays(14);
        
        $package = $customer->package;
        $totalAmount = $package->price; // Harga total yang sudah termasuk PPN
        
        // Hitung harga dasar dan PPN dari total harga
        $amount = round($totalAmount / (1 + $ppnRate), 2); // Harga dasar
        $taxAmount = round($totalAmount - $amount, 2); // Jumlah PPN
        $taxPercentage = 11; // Persentase PPN
        
        // Fee teknisi kosong (0)
        $technicianFeePercentage = 0;
        $technicianFeeAmount = 0;
        
        $lastInvoice = Invoice::latest('id')->first();
        $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        Invoice::create([
            'invoice_number' => $invoiceNumber,
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'amount' => $amount,
            'tax_percentage' => $taxPercentage,
            'tax_amount' => $taxAmount,
            'technician_fee_percentage' => $technicianFeePercentage,
            'technician_fee_amount' => $technicianFeeAmount,
            'total_amount' => $totalAmount,
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate,
            'status' => $status,
            'notes' => 'Pembayaran layanan internet untuk periode ' . $invoiceDate->format('F Y'),
            'created_by' => $admin->id,
        ]);
    }
}
