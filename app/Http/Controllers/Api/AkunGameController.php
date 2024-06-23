<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Models\AkunGame;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AkunGameController extends Controller
{
    public function index()
    {
        $akunGames = AkunGame::with(['game:id,icon', 'penjual:id'])
            ->select('id', 'penjual_id', 'game_id', 'judul', 'harga', 'gambar')
            ->where('status_akun', 'tersedia')
            ->orderBy('id', 'desc')
            ->get();
        return new ResponseResource(true, 'daftar akun game', $akunGames);
    }

    public function search(Request $request)
    {
        $query = AkunGame::with(['game:id,icon', 'penjual:id'])
            ->select('id', 'penjual_id', 'game_id', 'judul', 'harga', 'gambar')
            ->where('status_akun', 'tersedia')
            ->orderBy('id', 'desc');

        if ($request->has('game_id')) {
            $query->where('game_id', $request->input('game_id'));
        }

        if ($request->has('judul')) {
            $query->where('judul', 'like', '%' . $request->input('judul') . '%');
        }

        $akunGames = $query->get();

        return new ResponseResource(true, "daftar akun game dengan pencarian" . $request->judul . "", $akunGames);
    }

    public function show(string $id)
    {
        $transaksi = AkunGame::with(
            [
                'penjual:id,user_id' => [
                    'user:id,nama'
                ],
            ]
        )
            ->select('id', 'judul', 'deskripsi', 'gambar', 'harga', 'penjual_id')
            ->find($id);

        if ($transaksi) {
            return new ResponseResource(true, "Melihat Transaksi dengan id $id", $transaksi);
        } else {
            return new ResponseResource(false, "Transaksi dengan id $id tidak ditemukan", null);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'penjual_id' => 'required|integer|exists:users,id',
                'game_id' => 'required|integer|exists:games,id',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'gambar' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',
                'harga' => 'required|integer|min:0',
            ]);
        } catch (ValidationException $e) {
            return new ResponseResource(false, "Validator error", $e->errors());
        }

        if ($request->hasFile('gambar')) {
            $filePath = $request->file('gambar')->store('images/akun-game', 'public');
            $validated['gambar'] = $filePath;
        }

        $transaksi = AkunGame::create($validated);

        return new ResponseResource(true, "Berhasil membuat Akun Game", $transaksi);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'penjual_id' => 'required|integer|exists:users,id',
                'game_id' => 'required|integer|exists:games,id',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'gambar' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
                'harga' => 'required|integer|min:0',
            ]);
        } catch (ValidationException $e) {
            return new ResponseResource(false, "Validator error", $e->errors());
        }

        $transaksi = AkunGame::find($id);
        if (!$transaksi) {
            return new ResponseResource(false, "Akun Game dengan id $id tidak ditemukan", null);
        }

        if ($request->hasFile('gambar')) {
            $filePath = $request->file('gambar')->store('images/akun-game', 'public');
            $validated['gambar'] = $filePath;
        }

        $transaksi->update($validated);

        return new ResponseResource(true, "Berhasil mengupdate Akun Game", $transaksi);
    }


    public function destroy(string $id)
    {
        $transaksi = AkunGame::find($id);
        if ($transaksi) {
            $transaksi->delete();
            return new ResponseResource(true, "Berhasil menghapus item.", null);
        } else {
            return new ResponseResource(false, "Gagal menghapus item.", null);
        }
    }

    public function penjual(Request $request)
    {
        $idPenjual = $request->query('idpenjual');
        $status = $request->query('status');

        $akunGames = AkunGame::where('penjual_id', $idPenjual)
            ->select('id', 'penjual_id', 'game_id', 'judul', 'harga', 'gambar')
            ->where('status_akun', $status)
            ->orderBy('id', 'desc')
            ->get();

        return new ResponseResource(true, 'daftar akun game', $akunGames);
    }
}
