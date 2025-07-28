<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Invoice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomersImport implements ToModel
{
    public function model(array $row)
    {
        Log::info('Import row:', $row);
        if ($row[1] == 'Nama') {
            return null;
        }
        if (empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[7])) {
            Log::error('Data kosong pada baris:', $row);
            return null;
        }
        $package = Package::where('name', trim($row[4]))->first();
        if (!$package) {
            Log::error('Paket tidak ditemukan: ' . $row[4]);
            return null;
        }
        $rawDate = trim($row[6]);
        if (!$rawDate) {
            Log::error('Tanggal kosong pada baris:', $row);
            return null;
        }
        try {
            $billingDate = Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error('Format tanggal tidak valid: ' . $rawDate);
            return null;
        }
        // Cek email duplikat
        $existingCustomer = Customer::where('email', trim($row[2]))->first();
        if ($existingCustomer) {
            Log::warning('Email sudah ada: ' . $row[2]);
            return null;
        }
        $customer = new Customer([
            'name'         => trim($row[1]),
            'email'        => trim($row[2]),
            'phone'        => trim($row[3]),
            'package_id'   => $package->id,
            'is_active'    => strtolower(trim($row[5])) == 'aktif' ? 1 : 0,
            'billing_date' => $billingDate,
            'created_by'   => Auth::id(),
            'address'      => isset($row[7]) ? trim($row[7]) : null,
        ]);
        $customer->save();
        // Generate nomor invoice
        $lastInvoice = Invoice::latest()->first();
        $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        $basePrice = $package->base_price;
        $taxAmount = $package->tax_amount;
        $totalAmount = $package->price;
        $technicianFeePercentage = $package->technician_fee_percentage ?? 0;
        $technicianFeeAmount = round($basePrice * ($technicianFeePercentage / 100), 2);
        // Buat invoice tanpa invoice_number dulu
        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'invoice_date' => $billingDate,
            'due_date' => Carbon::parse($billingDate)->addDays(30)->format('Y-m-d'),
            'amount' => $basePrice,
            'tax_percentage' => 11,
            'tax_amount' => $taxAmount,
            'technician_fee_percentage' => $technicianFeePercentage,
            'technician_fee_amount' => $technicianFeeAmount,
            'total_amount' => $totalAmount,
            'status' => 'unpaid',
            'created_by' => Auth::id(),
        ]);

        // Update invoice_number setelah dapat ID
        // Generate kode acak unik
        $unique = false;
        while (!$unique) {
            $random = strtoupper(bin2hex(random_bytes(4))); // 8 karakter hex
            $invoiceNumber = 'INV-' . $random;
            if (!Invoice::where('invoice_number', $invoiceNumber)->exists()) {
                $unique = true;
            }
        }
        $invoice->invoice_number = $invoiceNumber;
        $invoice->save();
        return $customer;
    }
}