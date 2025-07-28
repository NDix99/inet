<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomersImport; // Nanti kita buat
use Illuminate\Support\Facades\Log;

class TechnicianController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Middleware untuk memastikan hanya teknisi yang dapat mengakses
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isTechnician()) {
                abort(403, 'Akses tidak diizinkan');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan dashboard Teknisi.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $technicianId = Auth::id();
        
        // Data untuk dashboard
        $totalCustomers = Customer::where('created_by', $technicianId)->count();
        $totalInvoices = Invoice::whereHas('customer', function($query) use ($technicianId) {
            $query->where('created_by', $technicianId);
        })->count();
        
        $latestCustomers = Customer::where('created_by', $technicianId)
            ->latest()
            ->take(5)
            ->get();
            
        // Pendapatan teknisi (fee)
        $totalFee = Invoice::whereHas('customer', function($query) use ($technicianId) {
            $query->where('created_by', $technicianId);
        })->where('status', 'paid')->sum('technician_fee_amount');
        
        return view('technician.dashboard', compact(
            'totalCustomers',
            'totalInvoices',
            'totalFee',
            'latestCustomers'
        ));
    }

    /**
     * Menampilkan daftar pelanggan teknisi.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerIndex()
    {
        $customers = Customer::where('created_by', Auth::id())->get();
        return view('technician.customer.index', compact('customers'));
    }

    /**
     * Menampilkan form tambah pelanggan.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerCreate()
    {
        $packages = Package::where('is_active', true)->get();
        return view('technician.customer.create', compact('packages'));
    }

    /**
     * Menyimpan data pelanggan baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'billing_date' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['is_active'] = true;

        $customer = Customer::create($data);

        // Buat invoice otomatis untuk customer baru
        $package = Package::find($customer->package_id);
        if ($package) {
            $lastInvoice = \App\Models\Invoice::latest()->first();
            $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 5, '0', STR_PAD_LEFT);
            $basePrice = $package->base_price;
            $taxAmount = $package->tax_amount;
            $totalAmount = $package->price;
            $technicianFeePercentage = $package->technician_fee_percentage ?? 0;
            $technicianFeeAmount = round($basePrice * ($technicianFeePercentage / 100), 2);

            \App\Models\Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customer->id,
                'package_id' => $package->id,
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'amount' => $basePrice,
                'tax_percentage' => 11,
                'tax_amount' => $taxAmount,
                'technician_fee_percentage' => $technicianFeePercentage,
                'technician_fee_amount' => $technicianFeeAmount,
                'total_amount' => $totalAmount,
                'status' => 'unpaid',
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('technician.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pelanggan.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerShow(Customer $customer)
    {
        // Memastikan pelanggan milik teknisi yang sedang login
        if ($customer->created_by !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan');
        }
        
        $invoices = $customer->invoices()->latest()->get();
        return view('technician.customer.show', compact('customer', 'invoices'));
    }

    /**
     * Menampilkan form edit pelanggan.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerEdit(Customer $customer)
    {
        // Memastikan pelanggan milik teknisi yang sedang login
        if ($customer->created_by !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan');
        }
        
        $packages = Package::where('is_active', true)->get();
        return view('technician.customer.edit', compact('customer', 'packages'));
    }

    /**
     * Memperbarui data pelanggan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function customerUpdate(Request $request, Customer $customer)
    {
        // Memastikan pelanggan milik teknisi yang sedang login
        if ($customer->created_by !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'billing_date' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $customer->update($request->all());

        return redirect()->route('technician.customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Menampilkan laporan keuangan teknisi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function financialReport(Request $request)
    {
        $technicianId = Auth::id();
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $invoices = Invoice::whereHas('customer', function($query) use ($technicianId) {
            $query->where('created_by', $technicianId);
        })->whereBetween('invoice_date', [$startDate, $endDate])
          ->with(['customer', 'package'])
          ->get();

        $totalFee = $invoices->where('status', 'paid')->sum('technician_fee_amount');
        $pendingFee = $invoices->whereIn('status', ['unpaid', 'overdue'])->sum('technician_fee_amount');

        return view('technician.financial.report', compact(
            'invoices',
            'startDate',
            'endDate',
            'totalFee',
            'pendingFee'
        ));
    }

    /**
     * Print invoice dan update status is_printed
     */
    public function invoicePrint(Invoice $invoice)
    {
        // Pastikan invoice milik teknisi yang sedang login
        if ($invoice->customer->created_by !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan');
        }
        $now = now();
        $userId = Auth::id();
        $update = [];
        if (!$invoice->is_printed_technician) {
            $update['is_printed_technician'] = true;
            $update['printed_at_technician'] = $now;
            $update['printed_by_technician'] = $userId;
        }
        if (!$invoice->is_printed_admin) {
            $update['is_printed_admin'] = true;
            $update['printed_at_admin'] = $now;
            $update['printed_by_admin'] = $userId;
        }
        if (!$invoice->is_printed_superadmin) {
            $update['is_printed_superadmin'] = true;
            $update['printed_at_superadmin'] = $now;
            $update['printed_by_superadmin'] = $userId;
        }
        if (!empty($update)) {
            $invoice->update($update);
        }
        return view('technician.invoice.print', compact('invoice'));
    }

    /**
     * Update invoice status (lunas/belum lunas) and synchronize to admin and superadmin
     */
    public function invoiceUpdateStatus(Request $request, Invoice $invoice)
    {
        // Pastikan invoice milik teknisi yang sedang login
        if ($invoice->customer->created_by !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan');
        }
        $request->validate([
            'status' => 'required|in:paid,unpaid,overdue',
        ]);
        $update = [
            'status' => $request->status,
        ];
        // Sinkronisasi ke admin & superadmin
        $invoice->update($update);
        // Jika status diubah ke paid/unpaid/overdue oleh teknisi, update juga di admin & superadmin (status sama)
        // (Status invoice memang satu kolom, jadi update otomatis terlihat di semua role)
        return redirect()->back()->with('success', 'Status invoice berhasil diperbarui.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new CustomersImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data pelanggan berhasil diimport!');
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
} 