<?php

namespace App\Http\Controllers\Api;

use App\Models\AkunGame;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ResponseResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

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

        $query = AkunGame::with(['game:id,icon', 'penjual:id,user_id' => ['user:id,nama']])
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

        return new ResponseResource(true, "daftar akun game dengan pencarian " . $request->input('judul') . " dan game id " .  $request->input('game_id'), $akunGames);    }

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
            $filePath = $request->file('gambar')->store('images/akun-game');
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
            if ($transaksi->gambar) {
                Storage::disk('public')->delete($transaksi->gambar);
            }

            try {
                $filePath = $request->file('gambar')->store('images/akun-game');
                $validated['gambar'] = $filePath;
                Log::info('File uploaded successfully: ' . $filePath);
            } catch (\Exception $e) {
                Log::error('File upload failed: ' . $e->getMessage());
                return new ResponseResource(false, "Gagal mengupload gambar.", null);
            }
        }

        $transaksi->update($validated);

        return new ResponseResource(true, "Berhasil mengupdate Akun Game", $transaksi);
    }



    public function destroy(string $id)
    {
        $akunGame = AkunGame::find($id);

        if ($akunGame) {
            if ($akunGame->status_akun == 'tersedia') {
                $akunGame->delete();
                return new ResponseResource(true, "Berhasil menghapus item.", null);
            } else {
                return new ResponseResource(false, "Item tidak bisa dihapus karena status bukan 'tersedia'.", null);
            }
        } else {
            return new ResponseResource(false, "Item tidak ditemukan.", null);
        }
    }

    public function penjual(Request $request, string $id)
    {
        $status = $request->query('status');

        $akunGames = AkunGame::where('penjual_id', $id)
            ->select('id', 'penjual_id', 'game_id', 'judul', 'deskripsi', 'harga', 'gambar')
            ->where('status_akun', $status)
            ->orderBy('id', 'desc')
            ->get();

        return new ResponseResource(true, 'daftar akun game', $akunGames);
    }
}
