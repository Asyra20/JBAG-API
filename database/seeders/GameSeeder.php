<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('games')->insert([
            [
                'nama' => 'Honor of Kings',
                'icon' => '/images/hok.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Free Fire',
                'icon' => '/images/ff.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Mobile Legends',
                'icon' => '/images/mole.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
