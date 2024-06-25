<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Models\Penilaian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function show(string $id)
    {
        $penilaian = Penilaian::with(['detailTransaksi'])
            ->get();

        return new ResponseResource(true, "penilaian dengan id $id", $penilaian);
    }

    public function store(Request $request)
    {
        $request->validate([
            'detail_transaksi_id' => 'required|exists:detail_transaksis,id',
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string',
        ]);

        $penilaian = new Penilaian;
        $penilaian->tanggal_penilaian = Carbon::now();
        $penilaian->detail_transaksi_id = $request->detail_transaksi_id;
        $penilaian->rating = $request->rating;
        $penilaian->review = $request->review;
        $penilaian->save();

        return new ResponseResource(true, 'berhasil membuat penilaian', $penilaian);
    }
}
