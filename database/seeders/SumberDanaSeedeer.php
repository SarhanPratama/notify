<?php

namespace Database\Seeders;

use App\Models\SumberDana;
use Illuminate\Database\Seeder;

class SumberDanaSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $sumberDana = ['Kas Seroo', 'Kas Rekening'];

           foreach ($sumberDana as $item) {
            SumberDana::factory()->create([
                'nama' => $item,
                'saldo_awal' => rand(1000000, 5000000),
            ]);
        }
    }
}
