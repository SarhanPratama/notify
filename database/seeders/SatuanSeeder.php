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
        $satuanBarang = ['PCS', 'BOX', 'ROLL', 'PACK', 'RIM', 'UNIT', 'LUSIN', 'GROSS', 'KG', 'GRAM', 'LITER', 'ML', 'METER', 'CM', 'KARTON', 'SET', 'KODI', 'LEMBAR', 'BOTOL', 'KALENG'];

        foreach ($satuanBarang as $satuan) {
            \App\Models\Satuan::factory()->create([
                'nama' => $satuan
            ]);
        }
    }
}
