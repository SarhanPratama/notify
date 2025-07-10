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
            'tgl_lahir' => '2004-04-27',
            'telepon' => '0895368765',
            'alamat' => 'JL Kubang Raya - Panam, Tarai Bangun, Kec. Tambang, Kabupaten Kampar, Riau 28293',
            'id_role' => $role->id,
            'password' => Hash::make('admin123'),
        ]);
        $admin->assignRole('owner');

        // $karyawan = User::create([
        //     'name' => 'Karyawan',
        //     'email' => 'karyawan@example.com',
        //     'password' => Hash::make('karyawan123'),
        // ]);
        // $karyawan->assignRole('karyawan');
    }
}
