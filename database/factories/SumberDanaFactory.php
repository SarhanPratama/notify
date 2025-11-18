<?php

namespace Database\Factories;

use App\Models\SumberDana;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SumberDana>
 */
class SumberDanaFactory extends Factory
{

    protected $model = SumberDana::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word,
            'saldo_awal' => $this->faker->numberBetween(100000, 10000000),
        ];
    }
}
