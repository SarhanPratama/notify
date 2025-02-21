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
        $admin = Role::create(['name' => 'admin']);
        $keuangan = Role::create(['name' => 'keuangan']);
        $gudang = Role::create(['name' => 'gudang']);
        $karyawan = Role::create(['name' => 'karyawan']);

               // Buat Permission
               $permissions = [
                // Admin
                'manage users',
                'manage roles',
                'view reports',
                'manage products',
                'manage inventory',

                // Keuangan
                'view transactions',
                'manage transactions',
                'approve payments',
                'view financial reports',

                // Gudang
                'view inventory',
                // 'manage inventory',
                'request stock',

                // Karyawan
                'view products',
                'create orders',
                'process orders',
            ];

            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
            }

            // Berikan Permission ke Role
            $admin->givePermissionTo(['manage users', 'manage roles', 'view reports', 'manage products', 'manage inventory']);
            $keuangan->givePermissionTo(['view transactions', 'manage transactions', 'approve payments', 'view financial reports']);
            $gudang->givePermissionTo(['view inventory', 'manage inventory', 'request stock']);
            $karyawan->givePermissionTo(['view products', 'create orders', 'process orders']);
        }
    }
