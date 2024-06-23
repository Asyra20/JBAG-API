<?php

use App\Http\Controllers\Api\AkunGameController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\KeranjangController;
use App\Http\Controllers\Api\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/keranjang/user/{id}', [KeranjangController::class, 'index']);
Route::post('/keranjang', [KeranjangController::class, 'store']);
Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy']);

Route::get('/checkout', [CheckoutController::class, 'paymentMthodPenjual']);

Route::get('/transaksi/user/{id}', [TransaksiController::class, 'index']);
Route::post('/transaksi', [TransaksiController::class, 'store']);
Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);
Route::post('/transaksi/update/{id}', [TransaksiController::class, 'update']);
Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);

Route::get('/akungame', [AkunGameController::class, 'index']);
Route::get('/akungame/search', [AkunGameController::class, 'search']);
Route::get('/akungame/{id}', [AkunGameController::class, 'show']);
Route::post('/akungame', [AkunGameController::class, 'store']);
Route::post('/akungame/update/{id}', [AkunGameController::class, 'update']);
Route::delete('/akungame/{id}', [AkunGameController::class, 'destroy']);

Route::get('/akungame/penjual/{id}', [AkunGameController::class, 'penjual']);
