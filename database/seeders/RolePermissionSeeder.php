<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus permission lama jika ada
        Permission::query()->delete();

        $permissions = [
            // Dashboard
            'dashboard',

            // Data Master
            'kategori',
            'bahan-baku',
            'supplier',
            'outlet',

            // Transaksi Gudang
            'pembelian',
            'penjualan',
            'pesanan',

            // Keuangan
            'kas',
            'piutang',
            'transaksi',
            'kategori-keuangan',

            // Laporan
            'laporan',

            // Pengguna & Akses
            'users',
            'akses-role',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $keuangan = Role::firstOrCreate(['name' => 'keuangan']);
        $gudang = Role::firstOrCreate(['name' => 'gudang']);

        // Owner - Full Access
        $owner->givePermissionTo(Permission::all());

        // Gudang/Inventory - Akses modul gudang
        $gudang->givePermissionTo([
            'dashboard',
            'kategori',
            'bahan-baku',
            'supplier',
            'outlet',
            'pembelian',
            'penjualan',
            'pesanan',
            'laporan'
        ]);

        // Keuangan - Akses modul keuangan
        $keuangan->givePermissionTo([
            'dashboard',
            'kategori-keuangan',
            'kas',
            'pesanan',
            'piutang',
            'transaksi',
            'laporan'
        ]);
    }
}
