<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Permissions
        Permission::create(['name' => 'manage users']); // Manajemen Pengguna
        Permission::create(['name' => 'manage products']); // Menambah/Update Produk
        Permission::create(['name' => 'view products']); // Melihat Daftar Produk
        Permission::create(['name' => 'delete products']); // Menghapus Produk
        Permission::create(['name' => 'process sales transactions']); // Transaksi Penjualan
        Permission::create(['name' => 'process return transactions']); // Transaksi Pengembalian
        Permission::create(['name' => 'view sales reports']); // Melihat Laporan Penjualan
        Permission::create(['name' => 'view stock reports']); // Melihat Laporan Stok
        Permission::create(['name' => 'system settings']); // Pengaturan Sistem
        Permission::create(['name' => 'manage discounts']); // Mengelola Diskon
        Permission::create(['name' => 'access customer data']); // Mengakses Data Pelanggan
        Permission::create(['name' => 'view transactions']); // Melihat Transaksi
        Permission::create(['name' => 'print receipts']); // Mencetak Struk

        // Membuat Roles dan meng-assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'manage users',
            'manage products',
            'view products',
            'delete products',
            'process sales transactions',
            'process return transactions',
            'view sales reports',
            'view stock reports',
            'system settings',
            'manage discounts',
            'access customer data',
            'view transactions',
            'print receipts',
        ]);

        $kasir = Role::create(['name' => 'kasir']);
        $kasir->givePermissionTo([
            'view products',
            'process sales transactions',
            'process return transactions',
            'view transactions',
            'print receipts',
        ]);

        $manajer = Role::create(['name' => 'manajer']);
        $manajer->givePermissionTo([
            'view products',
            'manage products',
            'view sales reports',
            'view stock reports',
            'manage discounts',
            'access customer data',
            'view transactions',
            'print receipts',
        ]);

        $gudang = Role::create(['name' => 'gudang']);
        $gudang->givePermissionTo([
            'manage products',
            'view products',
            'delete products',
            'view stock reports',
        ]);
    }
}
