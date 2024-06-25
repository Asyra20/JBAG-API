<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GameController extends Controller
{
    public function index()
    {
        $game = Game::select('id', 'nama', 'icon')
            ->get();
        return new ResponseResource(true, 'daftar akun game', $game);
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama' => 'required|string|max:50',
                'icon' => 'required|image|max:10240', // 10240 KB = 10 MB
            ]);
        } catch (ValidationException $e) {
            return new ResponseResource(false, "Validator error", $e->errors());
        }

        // Handle file upload
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('images');
        }

        // Simpan data ke tabel games
        $game = new Game();
        $game->nama = $request->input('nama');
        $game->icon = $iconPath;
        $game->save();

        // Response setelah data disimpan
        return new ResponseResource(true, 'daftar akun game', $game); // 201
    }
}
