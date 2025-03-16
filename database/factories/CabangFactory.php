<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class cabangFactory extends Factory
{
    protected $model = \App\Models\Cabang::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->company,
            'telepon' => $this->faker->phoneNumber,
            'alamat' => $this->faker->address,
        ];
    }
}
