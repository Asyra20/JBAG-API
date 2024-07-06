<?php

use App\Http\Controllers\Api\AkunGameController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\EwalletController;
use App\Http\Controllers\Api\KeranjangController;
use App\Http\Controllers\Api\PenilaianController;
use App\Http\Controllers\Api\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::post('/register-user', [AuthController::class, 'registerUser']);
Route::post('/register-penjual', [AuthController::class, 'registerPenjual']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/keranjang/user/{id}', [KeranjangController::class, 'index']);
Route::post('/keranjang', [KeranjangController::class, 'store']);
Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy']);

Route::get('/checkout', [CheckoutController::class, 'paymentMthodPenjual']);

Route::get('/transaksi/user/{id}', [TransaksiController::class, 'index']);
Route::get('/transaksi/penjual/{id}', [TransaksiController::class, 'penjual']);
Route::post('/transaksi', [TransaksiController::class, 'store']);
Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);
Route::post('/transaksi/update/{id}', [TransaksiController::class, 'update']);
Route::get('/transaksi/{id}/bukti-pembayaran', [TransaksiController::class, 'lihatBuktiPembayaran']);
Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);

Route::patch('/kirim-akun', [TransaksiController::class, 'kirimAkun']);

Route::get('/akungame', [AkunGameController::class, 'index']);
Route::get('/akungame/search', [AkunGameController::class, 'search']);
Route::get('/akungame/{id}', [AkunGameController::class, 'show']);
Route::post('/akungame', [AkunGameController::class, 'store']);
Route::post('/akungame/update/{id}', [AkunGameController::class, 'update']);
Route::delete('/akungame/{id}', [AkunGameController::class, 'destroy']);

Route::get('/akungame/penjual/{id}', [AkunGameController::class, 'penjual']);

Route::get('/penilaian/{id}', [PenilaianController::class, 'show']);
Route::post('/penilaian', [PenilaianController::class, 'store']);

Route::get('/games', [GameController::class, 'index']);
Route::post('/games', [GameController::class, 'store']);

Route::get('/ewallets', [EwalletController::class, 'index']);
Route::post('/ewallets', [EwalletController::class, 'store']);
