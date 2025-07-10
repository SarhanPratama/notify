<?php

namespace Database\Factories;

use App\Models\Satuan;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BahanBaku>
 */
class BahanBakuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->word,
            'harga' => $this->faker->randomFloat(2, 1000, 100000),
            'stok_awal' => $this->faker->numberBetween(10, 100),
            'stok_minimum' => $this->faker->numberBetween(1, 10),
            'foto' => null, 
            'id_kategori' => Kategori::inRandomOrder()->first()?->id ?? Kategori::factory(),
            'id_satuan' => Satuan::inRandomOrder()->first()?->id ?? Satuan::factory(),
        ];
    }
}
