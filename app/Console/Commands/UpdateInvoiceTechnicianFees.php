<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\User;

class UpdateInvoiceTechnicianFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:update-technician-fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update technician fees for existing invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating technician fees for existing invoices...');
        
        $invoices = Invoice::where('technician_fee_amount', 0)->get();
        $updated = 0;
        
        foreach ($invoices as $invoice) {
            if ($invoice->created_by) {
                $technician = User::find($invoice->created_by);
                
                if ($technician && $technician->isTechnician() && $technician->technician_fee_percentage > 0) {
                    $feeCalculation = $technician->calculateTechnicianFee($invoice->total_amount);
                    
                    $invoice->update([
                        'technician_fee_percentage' => $feeCalculation['percentage'],
                        'technician_fee_amount' => $feeCalculation['amount']
                    ]);
                    
                    $updated++;
                }
            }
        }
        
        $this->info("Updated {$updated} invoices with technician fees.");
        
        return 0;
    }
}
