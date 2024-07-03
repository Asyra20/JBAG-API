<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserAndPenjualSeeder::class,
            EwalletSeeder::class,
            GameSeeder::class,
            AkunGameSeeder::class,
            // KeranjangSeeder::class,
            // TransaksiSeeder::class,
            // DetailTransaksiSeeder::class,
            // PenilaianSeeder::class,
        ]);
    }
}
