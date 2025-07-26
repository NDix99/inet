<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan ID peran teknisi
        $technicianRole = Role::where('name', 'technician')->first();

        if ($technicianRole) {
            // Membuat akun teknisi
            User::create([
                'name' => 'Teknisi 1',
                'email' => 'teknisi1@example.com',
                'password' => Hash::make('password'),
                'role_id' => $technicianRole->id,
                'phone' => '08123456787',
                'is_active' => true,
            ]);

            User::create([
                'name' => 'Teknisi 2',
                'email' => 'teknisi2@example.com',
                'password' => Hash::make('password'),
                'role_id' => $technicianRole->id,
                'phone' => '08123456786',
                'is_active' => true,
            ]);
        }
    }
} 