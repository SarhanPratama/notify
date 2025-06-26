<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satuanBarang = ['Pcs', 'Box', 'Roll', 'Pack', 'Rim', 'Unit', 'Lusin', 'Gross', 'Kg', 'Gram', 'Liter', 'Ml', 'Meter', 'Cm', 'Karton', 'Set', 'Kodi', 'Lembar', 'Botol', 'Kaleng'];

        foreach ($satuanBarang as $satuan) {
            \App\Models\Satuan::factory()->create([
                'nama' => $satuan
            ]);
        }
    }
}
