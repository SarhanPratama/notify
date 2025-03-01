<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $kategori = ['olahan', 'non olahan'];

        foreach ($kategori as $satuan) {
            \App\Models\Kategori::factory()->create([
                'nama' => $satuan
            ]);
        }
    }
}
