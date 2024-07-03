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
            'nama' => 'dzaky',
            'username' => 'dzaky',
            'email' => 'zaaaafl654@gmail.com',
            'password' => Hash::make('dz'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert pembeli user
        DB::table('users')->insert([
            'role' => 'pembeli',
            'nama' => 'asira',
            'username' => 'asira',
            'email' => 'rasyraf7@gmail.com',
            'password' => Hash::make('asira'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert pembeli user
        DB::table('users')->insert([
            'role' => 'pembeli',
            'nama' => 'khonif',
            'username' => 'khonif',
            'email' => 'oniefzk11@gmail.com',
            'password' => Hash::make('khonif'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert pembeli user
        DB::table('users')->insert([
            'role' => 'pembeli',
            'nama' => 'nopal',
            'username' => 'nopal',
            'email' => 'nauvalrizkyr13@gmail.com',
            'password' => Hash::make('nopal'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert pembeli user
        DB::table('users')->insert([
            'role' => 'pembeli',
            'nama' => 'puj',
            'username' => 'pujangga',
            'email' => 'pujanggaangga96@gmail.com',
            'password' => Hash::make('pujangga'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // Insert penjual users
        $penjualId1 = DB::table('users')->insertGetId([
            'role' => 'penjual',
            'nama' => 'NopGaming Store',
            'username' => 'nopstore',
            'email' => 'nopstore@jbag.com',
            'password' => Hash::make('nopstore'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $penjualId2 = DB::table('users')->insertGetId([
            'role' => 'penjual',
            'nama' => 'Dzzzzz Store',
            'username' => 'dzzzstore',
            'email' => 'dzzstore@jbag.com',
            'password' => Hash::make('dzstore'),
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
