<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Merek;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\KategoriSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // $this->call([
        //     SatuanSeeder::class,
        // ]);

        $this->call([
            MerekSeeder::class,
        ]);

        // $this->call([
        //     KategoriSeeder::class,
        // ]);

        // $this->call([
        //     RolePermissionSeeder::class,
        //     UserSeeder::class,
        // ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',

        // ]);
        Merek::factory(10)->create();
    }
}
