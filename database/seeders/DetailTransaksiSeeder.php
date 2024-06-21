<?php

namespace Database\Seeders;

use App\Models\DetailTransaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DetailTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $detailTransaksis = [
            [
                'transaksi_id' => 1,
                'akun_game_id' => 3,
                'uid_akun' => 'UID789012',
                'email_akun' => 'email2@example.com',
                'password_akun' => Hash::make('pass-bukan-pass'),
            ],
            [
                'transaksi_id' => 1,
                'akun_game_id' => 2,
                'uid_akun' => 'UID123456',
                'email_akun' => 'email1@example.com',
                'password_akun' => Hash::make('satuduatiga123'),
            ],
            [
                'transaksi_id' => 2,
                'akun_game_id' => 1,
                'uid_akun' => '753802600',
                'email_akun' => 'zaaaafl@gmail.com',
                'password_akun' => Hash::make('hahainipassnya'),
            ],
        ];

        foreach ($detailTransaksis as $detailTransaksi) {
            DetailTransaksi::create($detailTransaksi);
        }
    }
}
