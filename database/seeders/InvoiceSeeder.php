<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan data pelanggan
        $customers = Customer::all();
        if ($customers->isEmpty()) {
            return;
        }

        // Mendapatkan admin untuk created_by
        $admin = User::whereHas('role', function($query) {
            $query->where('name', 'admin');
        })->first();

        if (!$admin) {
            return;
        }

        // Untuk setiap pelanggan, buat beberapa invoice
        foreach ($customers as $customer) {
            // Invoice bulan ini (belum lunas)
            $this->createInvoice($customer, $admin, 0, 'unpaid');
            
            // Invoice bulan lalu (lunas)
            $this->createInvoice($customer, $admin, -1, 'paid');
            
            // Invoice 2 bulan lalu (lunas)
            $this->createInvoice($customer, $admin, -2, 'paid');
        }
        
        // Buat beberapa invoice terlambat
        $lateCustomers = $customers->random(2);
        foreach ($lateCustomers as $customer) {
            $this->createInvoice($customer, $admin, -3, 'overdue');
        }
    }
    
    /**
     * Membuat invoice untuk pelanggan
     */
    private function createInvoice($customer, $admin, $monthOffset, $status)
    {
        $invoiceDate = Carbon::now()->addMonths($monthOffset)->startOfMonth();
        $dueDate = clone $invoiceDate;
        $dueDate->addDays(14);
        
        $package = $customer->package;
        $amount = $package->price;
        
        // Menghitung PPN (11%)
        $taxPercentage = 11;
        $taxAmount = $amount * ($taxPercentage / 100);
        
        // Fee teknisi kosong (0)
        $technicianFeePercentage = 0;
        $technicianFeeAmount = 0;
        
        // Total yang harus dibayar pelanggan (subtotal + PPN)
        $totalAmount = $amount + $taxAmount;
        
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