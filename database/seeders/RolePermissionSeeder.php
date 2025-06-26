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
        $permissions = [
            'dashboard.view',
            'produk.view', 'discount.view',
            'kas.view', 'kas.manage',
            'pembelian.view', 'penjualan.view',
            'supplier.view', 'bahan-baku.view',
            'laporan.view',
            'users.view', 'cabang.view',
            'role.view', 'permission.view', 'akses-role.manage'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $keuangan = Role::firstOrCreate(['name' => 'keuangan']);
        $gudang = Role::firstOrCreate(['name' => 'gudang']);

        $owner->givePermissionTo(Permission::all());

        $keuangan->givePermissionTo([
            'dashboard.view', 'kas.view', 'kas.manage',
            'pembelian.view', 'penjualan.view', 'laporan.view'
        ]);

        $gudang->givePermissionTo([
            'dashboard.view',
            'produk.view', 'bahan-baku.view',
            'supplier.view', 'pembelian.view',
            'laporan.view'
        ]);
    }
}
