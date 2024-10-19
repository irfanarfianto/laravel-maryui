<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah role Admin sudah ada, jika tidak, buat role baru
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Membuat pengguna Admin baru
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        // Memberikan role Admin kepada pengguna
        $adminUser->assignRole($adminRole);
    }
}
