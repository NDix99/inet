<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class SetTechnicianFeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technicianRole = Role::where('name', 'technician')->first();
        
        if ($technicianRole) {
            $technicians = User::where('role_id', $technicianRole->id)->get();
            
            foreach ($technicians as $technician) {
                // Set fee default 10% jika belum diatur
                if ($technician->technician_fee_percentage == 0) {
                    $technician->update([
                        'technician_fee_percentage' => 10.00, // 10% default
                        'technician_fee_amount' => 0 // Akan dihitung otomatis
                    ]);
                }
            }
        }
    }
}
