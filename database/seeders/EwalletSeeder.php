<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EwalletSeeder extends Seeder
{
    public function run()
    {
        DB::table('ewallets')->insert([
            [
                'nama' => 'Gopay',
                'icon' => 'images/logo-gopay.png', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ovo',
                'icon' => 'images/logo-ovo.png', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dana',
                'icon' => 'images/logo-dana.png', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
