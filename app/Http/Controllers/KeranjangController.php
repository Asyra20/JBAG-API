<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index($userId)
    {
        // Mengambil semua item keranjang berdasarkan user ID
        $keranjang = Keranjang::where('user_id', $userId)
            ->with('akunGame:id,judul,harga')
            ->get();

        return response()->json($keranjang);
    }

    public function store(Request $request)
    {
        // Menambahkan item ke keranjang
        $keranjang = new Keranjang;
        $keranjang->user_id = $request->user_id;
        $keranjang->product_id = $request->product_id;
        $keranjang->save();

        return response()->json(['message' => 'Item added to cart successfully.']);
    }

    public function delete($id)
    {
        // Menghapus item dari keranjang berdasarkan ID item keranjang
        $keranjang = Keranjang::find($id);
        if ($keranjang) {
            $keranjang->delete();
            return response()->json(['message' => 'Item removed from cart successfully.']);
        } else {
            return response()->json(['message' => 'Item not found.'], 404);
        }
    }
}
