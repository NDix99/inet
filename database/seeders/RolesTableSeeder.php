<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat peran superadmin
        Role::create([
            'name' => 'superadmin',
            'description' => 'Super Administrator dengan akses penuh ke sistem',
        ]);

        // Membuat peran admin
        Role::create([
            'name' => 'admin',
            'description' => 'Administrator dengan akses terbatas',
        ]);

        // Membuat peran teknisi
        Role::create([
            'name' => 'technician',
            'description' => 'Teknisi yang menangani pelanggan',
        ]);
    }
}
