<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Models\AkunGame;
use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index(string $userId)
    {
        // Mengambil semua item keranjang berdasarkan user ID
        $keranjang = Keranjang::where('user_id', $userId)
            ->with([
                'akunGame:id,penjual_id,judul,harga' => [
                    'penjual:id,user_id' => [
                        'user:id,nama'
                    ]
                ]
            ])
            ->latest()
            ->select('id', 'user_id', 'akun_game_id')
            ->get();

        return new ResponseResource(true, "List Keranjang user $userId", $keranjang);
    }

    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'akun_game_id' => 'required|exists:akun_games,id',
        ]);

        $akunGame = AkunGame::find($request->akun_game_id);
        if ($akunGame && $akunGame->status_akun != "tersedia") {
            return new ResponseResource(false, "Akun Game tidak tersedia", null);
        }

        // Cek apakah akun_game_id sudah ada di keranjang user
        $existingKeranjang = Keranjang::where('user_id', $request->user_id)
            ->where('akun_game_id', $request->akun_game_id)
            ->first();
        if ($existingKeranjang) {
            return new ResponseResource(false, "Item sudah ada di keranjang", null);
        }

        // Cari transaksi berdasarkan akun_game_id
        $transaksi = DetailTransaksi::where('akun_game_id', $request->akun_game_id)->first();
        // Jika ada transaksi dengan akun_game_id tersebut, cek status pembayarannya
        if ($transaksi && $transaksi->status_pembayaran == 'sudah_bayar') {
            return new ResponseResource(false, "Akun Game tidak tersedia", null);
        }

        // Menambahkan item ke keranjang
        $keranjang = new Keranjang;
        $keranjang->user_id = $request->user_id;
        $keranjang->akun_game_id = $request->akun_game_id;
        $keranjang->save();

        return new ResponseResource(true, "Sukses menambahkan item ke keranjang.", $keranjang);
    }

    public function destroy(string $id)
    {
        // Menghapus item dari keranjang berdasarkan ID item keranjang
        $keranjang = Keranjang::find($id);
        if ($keranjang) {
            $keranjang->delete();
            return new ResponseResource(true, "Berhasil menghapus item.", null);
        } else {
            return new ResponseResource(false, "Gagal menghapus item.", null);
        }
    }
}
