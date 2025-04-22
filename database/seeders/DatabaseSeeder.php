<?php

namespace Database\Seeders;


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

        // $this->call([
        //     MerekSeeder::class,
        // ]);

        // $this->call([
        //     KategoriSeeder::class,
        // ]);

        $this->call([
            // CabangSeeder::class,
            // MerekSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            KategoriSeeder::class,
            SatuanSeeder::class,

        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',

        // ]);
        // Merek::factory(10)->create();
    }
}
