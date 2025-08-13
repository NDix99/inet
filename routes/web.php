<?php

use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\MitraReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route untuk SuperAdmin
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth', 'role:superadmin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    // Manajemen Admin
    Route::get('/admins', [SuperAdminController::class, 'adminIndex'])->name('admins.index');
    Route::get('/admins/create', [SuperAdminController::class, 'adminCreate'])->name('admins.create');
    Route::post('/admins', [SuperAdminController::class, 'adminStore'])->name('admins.store');
    Route::get('/admins/{admin}/edit', [SuperAdminController::class, 'adminEdit'])->name('admins.edit');
    Route::put('/admins/{admin}', [SuperAdminController::class, 'adminUpdate'])->name('admins.update');
    Route::delete('/admins/{admin}', [SuperAdminController::class, 'adminDestroy'])->name('admins.destroy');
    
    // Manajemen Teknisi
    Route::get('/technicians', [SuperAdminController::class, 'technicianIndex'])->name('technicians.index');
    Route::get('/technicians/create', [SuperAdminController::class, 'technicianCreate'])->name('technicians.create');
    Route::post('/technicians', [SuperAdminController::class, 'technicianStore'])->name('technicians.store');
    Route::get('/technicians/{technician}/edit', [SuperAdminController::class, 'technicianEdit'])->name('technicians.edit');
    Route::put('/technicians/{technician}', [SuperAdminController::class, 'technicianUpdate'])->name('technicians.update');
    Route::delete('/technicians/{technician}', [SuperAdminController::class, 'technicianDestroy'])->name('technicians.destroy');
    
    // Manajemen Paket Internet
    Route::get('/packages', [SuperAdminController::class, 'packageIndex'])->name('packages.index');
    Route::get('/packages/create', [SuperAdminController::class, 'packageCreate'])->name('packages.create');
    Route::post('/packages', [SuperAdminController::class, 'packageStore'])->name('packages.store');
    Route::get('/packages/{package}/edit', [SuperAdminController::class, 'packageEdit'])->name('packages.edit');
    Route::put('/packages/{package}', [SuperAdminController::class, 'packageUpdate'])->name('packages.update');
    Route::delete('/packages/{package}', [SuperAdminController::class, 'packageDestroy'])->name('packages.destroy');
    
    // Manajemen Invoice
    Route::get('/invoices', [SuperAdminController::class, 'invoiceIndex'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [SuperAdminController::class, 'invoiceShow'])->name('invoices.show');
    Route::get('/invoices/{invoice}/print', [SuperAdminController::class, 'invoicePrint'])->name('invoices.print');
    Route::delete('/invoices/{invoice}', [SuperAdminController::class, 'invoiceDestroy'])->name('invoices.destroy');
    
    // Laporan Keuangan
    Route::get('/financial/report', [SuperAdminController::class, 'financialReport'])->name('financial.report');
    Route::get('/financial/report/print', [SuperAdminController::class, 'financialReportPrint'])->name('financial.report.print');
    
    // Manajemen Pelanggan
    Route::get('/customers', [SuperAdminController::class, 'customerIndex'])->name('customers.index');
    Route::get('/customers/{customer}', [SuperAdminController::class, 'customerShow'])->name('customers.show');
    Route::delete('/customers/{customer}', [SuperAdminController::class, 'customerDestroy'])->name('customers.destroy');
});

// Route untuk Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Manajemen Teknisi
    Route::get('/technicians', [AdminController::class, 'technicianIndex'])->name('technicians.index');
    Route::get('/technicians/create', [AdminController::class, 'technicianCreate'])->name('technicians.create');
    Route::post('/technicians', [AdminController::class, 'technicianStore'])->name('technicians.store');
    Route::get('/technicians/{technician}/edit', [AdminController::class, 'technicianEdit'])->name('technicians.edit');
    Route::put('/technicians/{technician}', [AdminController::class, 'technicianUpdate'])->name('technicians.update');
    Route::delete('/technicians/{technician}', [AdminController::class, 'technicianDestroy'])->name('technicians.destroy');
    
    // Manajemen Paket (hanya untuk edit harga)
    Route::get('/packages', [AdminController::class, 'packageIndex'])->name('packages.index');
    Route::get('/packages/{package}/edit', [AdminController::class, 'packageEdit'])->name('packages.edit');
    Route::put('/packages/{package}/price', [AdminController::class, 'packageUpdatePrice'])->name('packages.update.price');
    
    // Manajemen Invoice
    Route::get('/invoices', [AdminController::class, 'invoiceIndex'])->name('invoices.index');
    Route::get('/invoices/create', [AdminController::class, 'invoiceCreate'])->name('invoices.create');
    Route::post('/invoices', [AdminController::class, 'invoiceStore'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [AdminController::class, 'invoiceShow'])->name('invoices.show');
    Route::get('/invoices/{invoice}/print', [AdminController::class, 'invoicePrint'])->name('invoices.print');
    Route::put('/invoices/{invoice}/status', [AdminController::class, 'invoiceUpdateStatus'])->name('invoices.update.status');
    Route::put('/invoices/{invoice}/reset-print-status', [AdminController::class, 'invoiceResetPrintStatus'])->name('invoices.reset.print.status');
    
    // Laporan Keuangan
    Route::get('/financial/report', [AdminController::class, 'financialReport'])->name('financial.report');
    Route::get('/financial/report/print', [AdminController::class, 'financialReportPrint'])->name('financial.report.print');
    Route::post('/financial/report/reset-print-status', [MitraReportController::class, 'resetPrintStatus'])->name('financial.report.reset.print.status');
    Route::post('/financial/report/update-payment-status', [MitraReportController::class, 'updatePaymentStatus'])->name('financial.report.update.payment.status');
    
    // Manajemen Pelanggan
    Route::get('/customers', [AdminController::class, 'customerIndex'])->name('customers.index');
    Route::get('/customers/create', [AdminController::class, 'customerCreate'])->name('customers.create');
    Route::post('/customers', [AdminController::class, 'customerStore'])->name('customers.store');
    Route::get('/customers/{customer}', [AdminController::class, 'customerShow'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [AdminController::class, 'customerEdit'])->name('customers.edit');
    Route::put('/customers/{customer}', [AdminController::class, 'customerUpdate'])->name('customers.update');
    Route::delete('/customers/{customer}', [AdminController::class, 'customerDestroy'])->name('customers.destroy');
    
    // Export customers
    Route::post('/customers/export', [AdminController::class, 'exportCustomers'])->name('customers.export');
});

// Route untuk Teknisi
Route::prefix('technician')->name('technician.')->middleware(['auth', 'role:technician'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [TechnicianController::class, 'dashboard'])->name('dashboard');
    
    // Manajemen Pelanggan
    Route::get('/customers', [TechnicianController::class, 'customerIndex'])->name('customers.index');
    Route::get('/customers/create', [TechnicianController::class, 'customerCreate'])->name('customers.create');
    Route::post('/customers', [TechnicianController::class, 'customerStore'])->name('customers.store');
    Route::get('/customers/{customer}', [TechnicianController::class, 'customerShow'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [TechnicianController::class, 'customerEdit'])->name('customers.edit');
    Route::put('/customers/{customer}', [TechnicianController::class, 'customerUpdate'])->name('customers.update');
    
    // Laporan Keuangan Teknisi
    Route::get('/financial/report', [TechnicianController::class, 'financialReport'])->name('financial.report');

    // Print Invoice Teknisi
    Route::get('/invoices/{invoice}/print', [TechnicianController::class, 'invoicePrint'])->name('invoices.print');
    // Tambahkan route untuk update status lunas/belum lunas
    Route::put('/invoices/{invoice}/status', [TechnicianController::class, 'invoiceUpdateStatus'])->name('invoices.update.status');
});

Route::post('technician/customers/import', [App\Http\Controllers\TechnicianController::class, 'import'])->name('technician.customers.import');
