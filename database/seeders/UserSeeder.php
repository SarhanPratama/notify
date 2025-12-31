<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleOwner = Role::where('name', 'owner')->first();
        $roleGudang = Role::where('name', 'gudang')->first();
        $roleKeuangan = Role::where('name', 'keuangan')->first();

        $admin = User::create([
            'name' => 'Sarhan Pratama',
            'email' => 'admin@gmail.com',
            // 'id_role' => $roleOwner->id,
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('owner');

        $gudang = User::create([
            'name' => 'Gudang',
            'email' => 'gudang@gmail.com',
            // 'id_role' => $roleGudang->id,
            'password' => Hash::make('gudang123'),
            'email_verified_at' => now(),
        ]);
        $gudang->assignRole('gudang');

        $keuangan = User::create([
            'name' => 'Keuangan',
            'email' => 'keuangan@gmail.com',
            // 'id_role' => $roleKeuangan->id,
            'password' => Hash::make('keuangan123'),
            'email_verified_at' => now(),
        ]);
        $keuangan->assignRole('keuangan');
    }
}
