<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan ID peran superadmin
        $superadminRole = Role::where('name', 'superadmin')->first();

        if ($superadminRole) {
            // Membuat akun superadmin
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role_id' => $superadminRole->id,
                'phone' => '08123456789',
                'is_active' => true,
            ]);
        }
    }
}
