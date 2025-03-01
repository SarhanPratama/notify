<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Satuan>
 */
class SatuanFactory extends Factory
{
    protected $model = \App\Models\Satuan::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->randomElement(['PCS', 'BOX', 'ROLL', 'PACK', 'RIM', 'UNIT', 'LUSIN', 'GROSS', 'KG', 'GRAM', 'LITER', 'ML', 'METER', 'CM', 'KARTON', 'SET', 'KODI', 'LEMBAR', 'BOTOL', 'KALENG'])
        ];
    }
}
