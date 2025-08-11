<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Carbon\Carbon;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly invoices for all active customers on their billing date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $customers = Customer::where('is_active', true)->get();
        $created = 0;
        foreach ($customers as $customer) {
            if (!$customer->billing_date) continue;
            $billingDay = Carbon::parse($customer->billing_date)->day;
            // Cek jika hari ini adalah hari billing customer
            if ($today->day != $billingDay) continue;
            // Cek apakah sudah ada invoice untuk bulan ini
            $existing = Invoice::where('customer_id', $customer->id)
                ->whereMonth('invoice_date', $today->month)
                ->whereYear('invoice_date', $today->year)
                ->first();
            if ($existing) continue;
            $package = $customer->package;
            if (!$package) continue;
            $lastInvoice = Invoice::latest()->first();
            $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 5, '0', STR_PAD_LEFT);
            $basePrice = $package->base_price;
            $taxAmount = $package->tax_amount;
            $totalAmount = $package->price;
            $technicianFeePercentage = $package->technician_fee_percentage ?? 0;
            $technicianFeeAmount = round($basePrice * ($technicianFeePercentage / 100), 2);
            Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customer->id,
                'package_id' => $package->id,
                'invoice_date' => $today,
                'due_date' => $today->copy()->addDays(30),
                'amount' => $basePrice,
                'tax_percentage' => 11,
                'tax_amount' => $taxAmount,
                'technician_fee_percentage' => $technicianFeePercentage,
                'technician_fee_amount' => $technicianFeeAmount,
                'total_amount' => $totalAmount,
                'status' => 'unpaid',
                'created_by' => $customer->created_by,
            ]);
            $created++;
        }
        $this->info("{$created} invoice(s) generated.");
    }
}