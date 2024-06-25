<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAndPenjualSeeder extends Seeder
{
    public function run()
    {
        // Insert admin user
        DB::table('users')->insert([
            'role' => 'admin',
            'nama' => 'Admin',
            'username' => 'atmin',
            'email' => 'admin@jbag.com',
            'password' => Hash::make('ohMyAtmin'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert pembeli user
        DB::table('users')->insert([
            'role' => 'pembeli',
            'nama' => 'Pembeli',
            'username' => 'pembeliya',
            'email' => 'zaaaafl654@gmail.com',
            'password' => Hash::make('inisayapembeli'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // Insert penjual users
        $penjualId1 = DB::table('users')->insertGetId([
            'role' => 'penjual',
            'nama' => 'NopGaming Store',
            'username' => 'nopstore',
            'email' => 'nopstore@jbag.com',
            'password' => Hash::make('penjualpassword1'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $penjualId2 = DB::table('users')->insertGetId([
            'role' => 'penjual',
            'nama' => 'Dzzzzz Store',
            'username' => 'dzzzstore',
            'email' => 'dzzstore@jbag.com',
            'password' => Hash::make('penjualpassword2'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert penjual details
        DB::table('penjuals')->insert([
            [
                'user_id' => $penjualId1,
                'no_telp' => '085225001100',
                'alamat' => 'Alamat Penjual 1',
                'foto' => "images/foto-penjual/fotopenjual1.jpg",
                'ewallet_id' => 3, // Sesuaikan dengan ID ewallet yang ada
                'nama_profil_ewallet' => 'Nopal Gaming',
                'nomor_ewallet' => '1234567890',
                'is_verified' => 'true',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $penjualId2,
                'no_telp' => '085225041144',
                'alamat' => 'Alamat Penjual 2',
                'foto' => "images/foto-penjual/fotopenjual1.jpg",
                'ewallet_id' => 1, // Sesuaikan dengan ID ewallet yang ada
                'nama_profil_ewallet' => 'Dzaky Fadli',
                'nomor_ewallet' => '0987654321',
                'is_verified' => 'true',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
