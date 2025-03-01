<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Merek>
 */
class MerekFactory extends Factory
{
    protected $model = \App\Models\Merek::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->word(),
        ];
    }
}
