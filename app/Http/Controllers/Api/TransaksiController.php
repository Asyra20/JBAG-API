<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Models\AkunGame;
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

        // Cek apakah akun_game_id sudah ada di keranjang user
        $user = User::where('role', 'pembeli')
            ->where('id', $userId)
            ->first();
        if (!$user) {
            return new ResponseResource(false, "Transaksi user_id $userId tidak ditemukan", null);
        }

        // Mengambil semua item transaksi berdasarkan user ID
        $transaksi = Transaksi::where('user_id', $userId)
            ->select('id', 'tanggal_waktu', 'invoice', 'harga_total', 'status_pembayaran')
            ->get();

        return new ResponseResource(true, "Transaksi user $userId ditemukan", $transaksi);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_waktu' => 'required|date',
            'invoice' => 'required|string|max:15',
            'user_id' => 'required|exists:users,id',
            'penjual_id' => 'required|exists:penjuals,id',
            'nama_profil_ewallet' => 'required|string|max:20',
            'nomor_ewallet' => 'required|string|max:30',
            'harga_total' => 'required|integer',
            'detail_transaksis' => 'required|array',
            'detail_transaksis.*.akun_game_id' => 'required|exists:akun_games,id',
        ]);

        foreach ($validated['detail_transaksis'] as $detail) {
            $akunGame = AkunGame::find($detail['akun_game_id']);
            if ($akunGame->status_akun == 'terjual') {
                return new ResponseResource(false, 'Akun game dengan ID ' . $detail['akun_game_id'] . ' sudah terjual', null);
            }
        }

        $transaksi = Transaksi::create($validated);

        foreach ($validated['detail_transaksis'] as $detail) {
            $transaksi->detailTransaksi()->create($detail);
        }

        return new ResponseResource(true, "Berhasil membuat Transaksi", $transaksi->load('detailTransaksi'));
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
                'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
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
}
