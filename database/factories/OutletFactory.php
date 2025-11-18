<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OutletFactory extends Factory
{
    protected $model = \App\Models\Outlet::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->company,
            'kode' => 'ms',
            'telepon' => $this->faker->phoneNumber,
            'penanggung_jawab' => $this->faker->name,
            'alamat' => $this->faker->address,
            'lokasi' => $this->faker->imageUrl(640, 480, 'business', true, 'location'),
        ];
    }
}
