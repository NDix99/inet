<?php

namespace App\Http\Controllers;

use App\Models\MitraReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Reset status cetak laporan keuangan mitra.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPrintStatus(Request $request)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date',
        ]);

        $mitraReport = MitraReport::where('technician_id', $request->technician_id)
            ->where('periode_awal', $request->periode_awal)
            ->where('periode_akhir', $request->periode_akhir)
            ->first();

        if ($mitraReport) {
            $mitraReport->update([
                'is_printed' => false,
                'printed_at' => null,
                'printed_by' => null
            ]);
            return redirect()->back()->with('success', 'Status cetak laporan mitra berhasil direset.');
        }

        return redirect()->back()->with('error', 'Laporan mitra tidak ditemukan.');
    }

    /**
     * Update status pembayaran laporan keuangan mitra.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePaymentStatus(Request $request)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date',
            'is_paid' => 'required|boolean',
            'payment_notes' => 'nullable|string|max:255',
        ]);

        $mitraReport = MitraReport::where('technician_id', $request->technician_id)
            ->where('periode_awal', $request->periode_awal)
            ->where('periode_akhir', $request->periode_akhir)
            ->first();

        if (!$mitraReport) {
            // Jika belum ada, buat baru
            $mitraReport = new MitraReport([
                'technician_id' => $request->technician_id,
                'periode_awal' => $request->periode_awal,
                'periode_akhir' => $request->periode_akhir,
                'is_paid' => $request->is_paid,
                'payment_notes' => $request->payment_notes,
            ]);
            
            if ($request->is_paid) {
                $mitraReport->payment_date = now();
            } else {
                $mitraReport->payment_date = null;
            }
            
            $mitraReport->save();
            
            return redirect()->back()->with('success', 'Status pembayaran laporan mitra berhasil diperbarui.');
        }

        // Update status pembayaran
        $mitraReport->is_paid = $request->is_paid;
        $mitraReport->payment_notes = $request->payment_notes;
        
        if ($request->is_paid) {
            $mitraReport->payment_date = now();
        } else {
            $mitraReport->payment_date = null;
        }
        
        $mitraReport->save();

        return redirect()->back()->with('success', 'Status pembayaran laporan mitra berhasil diperbarui.');
    }
}
