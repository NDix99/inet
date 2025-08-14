<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;

class GenerateMonthlyInvoices extends Command
{
    protected $signature = 'invoices:generate-monthly';
    protected $description = 'Generate monthly invoices for all active customers on their billing date';

    public function handle()
    {
        $today = Carbon::today();
        $created = 0;

        $customers = Customer::where('is_active', true)
            ->whereDay('billing_date', $today->day) // langsung filter tanggal tagihan
            ->get();

        foreach ($customers as $customer) {
            // Skip kalau sudah ada invoice bulan ini
            $alreadyHasInvoice = Invoice::where('customer_id', $customer->id)
                ->whereMonth('invoice_date', $today->month)
                ->whereYear('invoice_date', $today->year)
                ->exists();

            if ($alreadyHasInvoice) {
                continue;
            }

            $package = $customer->package;
            if (!$package) {
                continue;
            }

            // Nomor invoice auto increment
            $lastInvoiceId = Invoice::max('id');
            $invoiceNumber = 'INV-' . str_pad(($lastInvoiceId ? $lastInvoiceId + 1 : 1), 5, '0', STR_PAD_LEFT);

            $basePrice = $package->base_price;
            $taxPercentage = 11; // bisa dibuat dynamic kalau mau
            $taxAmount = round($basePrice * ($taxPercentage / 100), 2);
            $totalAmount = $basePrice + $taxAmount;

            $technicianFeePercentage = $package->technician_fee_percentage ?? 0;
            $technicianFeeAmount = round($basePrice * ($technicianFeePercentage / 100), 2);

            Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customer->id,
                'package_id' => $package->id,
                'invoice_date' => $today,
                'due_date' => $today->copy()->addDays(30),
                'amount' => $basePrice,
                'tax_percentage' => $taxPercentage,
                'tax_amount' => $taxAmount,
                'technician_fee_percentage' => $technicianFeePercentage,
                'technician_fee_amount' => $technicianFeeAmount,
                'total_amount' => $totalAmount,
                'status' => 'unpaid',
                'created_by' => $customer->created_by,
            ]);

            $created++;
        }

        $this->info("{$created} invoice(s) generated for date {$today->toDateString()}.");
    }
}
