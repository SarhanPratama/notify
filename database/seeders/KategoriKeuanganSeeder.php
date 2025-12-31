<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\KategoriKeuangan;

class KategoriKeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // ['nama' => 'Penjualan', 'jenis' => 'pemasukan'],
            ['nama' => 'Operasional', 'jenis' => 'pengeluaran'],
            ['nama' => 'Gaji Karyawan', 'jenis' => 'pengeluaran'],
            ['nama' => 'Aset', 'jenis' => 'pengeluaran'],
        ];

        foreach ($categories as $cat) {
            KategoriKeuangan::firstOrCreate($cat);
        }
    }
}
