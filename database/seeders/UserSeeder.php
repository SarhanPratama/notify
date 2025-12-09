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
         $role = Role::where('name', 'owner')->first();

        $admin = User::create([
            'name' => 'Sarhan Pratama',
            'email' => 'admin@gmail.com',
            'id_role' => $role->id,
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('owner');

        $gudang = User::create([
            'name' => 'Gudang',
            'email' => 'gudang@gmail.com',
            'password' => Hash::make('gudang123'),
            'email_verified_at' => now(),
        ]);
        $gudang->assignRole('gudang');
    }
}
