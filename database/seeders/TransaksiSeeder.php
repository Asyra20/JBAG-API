<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $transaksis = [
            [
                'tanggal_waktu' => Carbon::now(),
                'user_id' => 2,
                'penjual_id' => 1,
                'nama_profil_ewallet' => 'Nopal Gaming',
                'nomor_ewallet' => '1234567890',
                'harga_total' => 300000,
                'bukti_pembayaran' => null,
                'status_pembayaran' => 'belum_bayar',
            ],
            [
                'tanggal_waktu' => Carbon::now(),
                'user_id' => 2,
                'penjual_id' => 2,
                'nama_profil_ewallet' => 'Dzaky Fadli',
                'nomor_ewallet' => '0987654321',
                'harga_total' => 600000,
                'bukti_pembayaran' => '/images/bukti-pembayaran/buktibayar1.png',
                'status_pembayaran' => 'sudah_bayar',
            ],
        ];

        foreach ($transaksis as $transaksi) {
            $epochTime = Carbon::now()->timestamp;
            $invoice = 'JINV-' . $epochTime;

            Transaksi::create([
                'tanggal_waktu' => $transaksi['tanggal_waktu'],
                'invoice' => $invoice,
                'user_id' => $transaksi['user_id'],
                'penjual_id' => $transaksi['penjual_id'],
                'nama_profil_ewallet' => $transaksi['nama_profil_ewallet'],
                'nomor_ewallet' => $transaksi['nomor_ewallet'],
                'harga_total' => $transaksi['harga_total'],
                'bukti_pembayaran' => $transaksi['bukti_pembayaran'],
                'status_pembayaran' => $transaksi['status_pembayaran'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
