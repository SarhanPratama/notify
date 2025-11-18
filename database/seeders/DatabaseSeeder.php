<?php

namespace Database\Seeders;



// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Database\Seeders\KategoriSeeder;
use Database\Seeders\SumberDanaSeedeer;

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
            OutletSeeder::class,
            SupplierSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            KategoriSeeder::class,
            SatuanSeeder::class,
            SumberDanaSeedeer::class,
            BahanBakuSeeder::class,
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',

        // ]);
        // Merek::factory(10)->create();
    }
}
