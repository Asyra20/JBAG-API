<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Models\AkunGame;
use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use App\Models\Penjual;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $userId)
    {
        $user = User::where('role', 'pembeli')
            ->where('id', $userId)
            ->first();
        if (!$user) {
            return new ResponseResource(false, "Transaksi user_id $userId tidak ditemukan", null);
        }

        // Mengambil semua item transaksi berdasarkan user ID
        $transaksi = Transaksi::where('user_id', $userId)
            ->select('id', 'invoice')
            ->orderBy('id', 'desc')
            ->get();

        return new ResponseResource(true, "Transaksi user $userId ditemukan", $transaksi);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanggal_waktu' => 'required|date',
                'invoice' => 'required|string|max:15',
                'user_id' => 'required|exists:users,id',
                'penjual_id' => 'required|exists:penjuals,id',
                'harga_total' => 'required|integer',
                'detail_transaksis' => 'required|array',
                'detail_transaksis.*.akun_game_id' => 'required|exists:akun_games,id',
            ]);
        } catch (ValidationException $e) {
            return new ResponseResource(false, "Validator error", $e->errors());
        }

        foreach ($validated['detail_transaksis'] as $detail) {
            $akunGame = AkunGame::find($detail['akun_game_id']);

            if (DetailTransaksi::find($detail['akun_game_id'])) {
                return new ResponseResource(false, 'Akun game dengan ID ' . $detail['akun_game_id'] . 'sudah ada di transaksi', null);
            }

            if ($akunGame->status_akun == 'terjual') {
                return new ResponseResource(false, 'Akun game dengan ID ' . $detail['akun_game_id'] . ' sudah terjual', null);
            }
        }

        // Ambil nama_profil_ewallet & nomor_ewallet berdasarkan penjual_id
        $penjual = Penjual::find($validated['penjual_id']);
        $namaProfilEwallet = $penjual->nama_profil_ewallet;
        $nomorEwallet = $penjual->nomor_ewallet;

        // Buat transaksi dengan data yang telah divalidasi
        $transaksiData = array_merge($validated, [
            'nama_profil_ewallet' => $namaProfilEwallet,
            'nomor_ewallet' => $nomorEwallet,
        ]);

        $transaksi = Transaksi::create($transaksiData);


        foreach ($validated['detail_transaksis'] as $detail) {
            $transaksi->detailTransaksi()->create($detail);

            // Hapus item keranjang berdasarkan akun_game_id
            Keranjang::where('akun_game_id', $detail['akun_game_id'])->delete();
        }

        return new ResponseResource(true, "Berhasil membuat Transaksi", ['transaksi_id' => $transaksi->id]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with(
            [
                'penjual:id,user_id,ewallet_id' => [
                    'user:id,nama', 'ewallet:id,nama,icon'
                ], 'detailTransaksi:id,transaksi_id,akun_game_id' => [
                    'akunGame:id,judul,harga'
                ]
            ]
        )
            ->select('id', 'penjual_id', 'tanggal_waktu', 'invoice', 'nama_profil_ewallet', 'nomor_ewallet', 'harga_total', 'status_pembayaran')
            ->find($id);

        if ($transaksi) {
            return new ResponseResource(true, "Melihat Transaksi dengan id $id", $transaksi);
        } else {
            return new ResponseResource(false, "Transaksi dengan id $id tidak ditemukan", null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
                'status_pembayaran' => 'required|in:proses_bayar,sudah_bayar',
            ]);
        } catch (ValidationException $e) {
            return new ResponseResource(false, "Validator error", $e->errors());
        }

        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return new ResponseResource(false, "Transaksi dengan id $id tidak ditemukan", null);
        }

        if ($transaksi->status_pembayaran == 'proses_bayar') {
            return new ResponseResource(false, "Transaksi ID $id sudah dalam proses bayar", null);
        }

        // // Handle file upload if it exists
        if ($request->hasFile('bukti_pembayaran')) {
            // Store new file
            $filePath = $request->file('bukti_pembayaran')->store('images/bukti-pembayaran');
            $transaksi->bukti_pembayaran = $filePath;
        }

        $transaksi->status_pembayaran = $validated['status_pembayaran'];
        $transaksi->save();

        return new ResponseResource(true, "Transaksi dengan id $id berhasil di update", $transaksi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksi = Transaksi::find($id);
        if ($transaksi) {
            $transaksi->delete();
            return new ResponseResource(true, "Berhasil menghapus item.", null);
        } else {
            return new ResponseResource(false, "Gagal menghapus item.", null);
        }
    }

    public function lihatBuktiPembayaran(string $id)
    {
        $transaksi = Transaksi::where('id', $id)->select('bukti_pembayaran')->get()->first();
        if ($transaksi->bukti_pembayaran != null) {
            return new ResponseResource(true, "Mengambil Bukti Transaksi", $transaksi);
        } else {
            return new ResponseResource(false, "Bukti Transaksi belum ada.", null);
        }
    }
}
