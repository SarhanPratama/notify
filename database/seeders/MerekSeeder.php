<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merekBarang = [
            'Sony', 'Samsung', 'LG', 'Apple', 'Xiaomi', 'Lenovo', 'Asus',
            'Dell', 'HP', 'Acer', 'Toshiba', 'Canon', 'Nikon', 'Panasonic',
            'Sharp', 'Philips', 'Sanyo', 'Polytron', 'Maspion', 'Miyako',
            'Electrolux', 'Cosmos', 'Seiko', 'Casio', 'Logitech', 'Microsoft',
            'Huawei', 'Oppo', 'Vivo', 'Realme', 'Nokia', 'Adidas', 'Nike',
            'Puma', 'Reebok', 'Converse', 'Fila', 'New Balance', 'Under Armour'
        ];

        foreach ($merekBarang as $merek) {
            \App\Models\Merek::factory()->create([
                'nama' => $merek
            ]);
        }
    }
}
