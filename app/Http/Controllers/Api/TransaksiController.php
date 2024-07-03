<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Mail\KirimAkun;
use App\Models\AkunGame;
use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use App\Models\Penjual;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $userId)
    {
        $status = $request->query('status');

        $user = User::where('role', 'pembeli')
            ->where('id', $userId)
            ->first();
        if (!$user) {
            return new ResponseResource(false, "Transaksi user_id $userId tidak ditemukan", null);
        }

        if ($status == "belum_bayar") {
            $transaksi = Transaksi::where('user_id', $userId)
                ->Where('status_pembayaran', $status)
                ->whereHas('detailTransaksi.akunGame', function ($query) {
                    $query->where('status_akun', 'tersedia');
                })
                ->select('id', 'invoice', 'status_pembayaran')
                ->orderBy('id', 'desc')
                ->get();
        }

        if ($status == "sudah_bayar") {
            $transaksi = Transaksi::where('user_id', $userId)
                ->Where('status_pembayaran', $status)
                ->whereHas('detailTransaksi.akunGame', function ($query) {
                    $query->where('status_akun', 'pending');
                })
                ->select('id', 'invoice', 'status_pembayaran')
                ->orderBy('id', 'desc')
                ->get();
        }

        if ($status == "terjual") {
            $transaksi = Transaksi::where('user_id', $userId)
                ->where('status_pembayaran', 'sudah_bayar')
                ->whereHas('detailTransaksi.akunGame', function ($query) {
                    $query->where('status_akun', 'terjual');
                })
                ->select('id', 'invoice', 'status_pembayaran')
                ->orderBy('id', 'desc')
                ->get();
        }

        return new ResponseResource(true, "Transaksi user $userId ditemukan", $transaksi);
    }

    /**
     * Display a listing of the resource.
     */
    public function penjual(Request $request, string $penjualId)
    {
        $status = $request->query('status');

        $penjual = Penjual::where('id', $penjualId)->first();
        if (!$penjual) {
            return new ResponseResource(false, "Penjual dengan id $penjualId tidak ditemukan", null);
        }

        if ($status == "pending") {
            $transaksi = Transaksi::where('penjual_id', $penjualId)
                ->Where('status_pembayaran', 'sudah_bayar')
                ->whereHas('detailTransaksi.akunGame', function ($query) {
                    $query->where('status_akun', 'pending');
                })
                ->select('id', 'invoice', 'status_pembayaran')
                ->orderBy('id', 'desc')
                ->get();
        }

        if ($status == "terjual") {
            $transaksi = Transaksi::where('penjual_id', $penjualId)
                ->where('status_pembayaran', 'sudah_bayar')
                ->whereHas('detailTransaksi.akunGame', function ($query) {
                    $query->where('status_akun', 'terjual');
                })
                ->select('id', 'invoice', 'status_pembayaran')
                ->orderBy('id', 'desc')
                ->get();
        }

        return new ResponseResource(true, "Transaksi penjual id $penjualId ditemukan", $transaksi);
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

            if ($akunGame->status_akun == 'pending') {
                return new ResponseResource(false, 'Akun game dengan ID ' . $detail['akun_game_id'] . ' sudah terbayar', null);
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
                ],
                'pembeli:id,email',
                'detailTransaksi:id,transaksi_id,akun_game_id' => [
                    'akunGame:id,judul,harga'
                ]
            ]
        )
            ->select('id', 'penjual_id', 'user_id', 'tanggal_waktu', 'invoice', 'nama_profil_ewallet', 'nomor_ewallet', 'harga_total', 'status_pembayaran')
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
                'status_pembayaran' => 'required',
            ]);
        } catch (ValidationException $e) {
            return new ResponseResource(false, "Validator error", $e->errors());
        }

        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return new ResponseResource(false, "Transaksi dengan id $id tidak ditemukan", null);
        }

        if ($transaksi->status_pembayaran == 'sudah_bayar') {
            return new ResponseResource(false, "Transaksi ID $id sudah bayar", null);
        }

        if ($request->hasFile('bukti_pembayaran')) {
            $filePath = $request->file('bukti_pembayaran')->store('images/bukti-pembayaran');
            $transaksi->bukti_pembayaran = $filePath;
        }

        $transaksi->status_pembayaran = $validated['status_pembayaran'];
        $transaksi->save();

        foreach ($transaksi->detailTransaksi as $detail) {
            $akunGame = $detail->akunGame;
            if ($akunGame) {
                $akunGame->status_akun = 'pending';
                $akunGame->save();
            }
        }


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

    public function kirimAkun(Request $request)
    {
        $emailPembeli = $request->input('email_pembeli');
        $subject = $request->input('subjek');
        $namaPenjual = $request->input('nama_penjual');
        $data = $request->input('akun');
        $deskripsi = $request->input('deskripsi');

        try {
            $request->validate([
                'email_pembeli' => 'required|email:rfc,dns'
            ]);

            foreach ($data as $item) {
                $detailTransaksi = DetailTransaksi::where('id', $item['id'])
                    ->where('akun_game_id', $item['akun_game_id'])
                    ->first();

                if ($detailTransaksi) {
                    $detailTransaksi->uid_akun = $item['uid_akun'];
                    $detailTransaksi->email_akun = $item['email_akun'];
                    $detailTransaksi->password_akun = $item['password_akun'];
                    $detailTransaksi->save();
                } else {
                    throw new \Exception('DetailTransaksi tidak ditemukan untuk id: ' . $item['id']);
                }

                $akunGame = AkunGame::find($item['akun_game_id']);
                if ($akunGame) {
                    $akunGame->status_akun = 'terjual';
                    $akunGame->save();
                } else {
                    throw new \Exception('AkunGame tidak ditemukan untuk id: ' . $item['akun_game_id']);
                }
            }

            Mail::to($emailPembeli)->send(new KirimAkun($data, $subject, $namaPenjual, $deskripsi));

            return new ResponseResource(true, "Berhasil mengirim mail", null);
        } catch (ValidationException $e) {
            return new ResponseResource(false, "Validator error", $e->errors());
        } catch (\Exception $e) {
            return new ResponseResource(false, "Terjadi kesalahan", $e->getMessage());
        } catch (\Throwable $e) {
            return new ResponseResource(false, "Unexpected error", $e->getMessage());
        }
    }
}
