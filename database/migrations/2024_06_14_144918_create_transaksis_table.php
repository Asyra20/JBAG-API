<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->timestamp('tanggal_waktu');
            $table->string('invoice', 10);
            $table->foreignId('user_id');
            $table->foreignId('penjual_id');
            $table->string('nama_profil_ewallet', 20);
            $table->string('nomor_ewallet', 30);
            $table->integer('harga_total', unsigned:true);
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->enum('status_pembayaran', ['belum_bayar', 'proses_bayar', 'sudah_bayar'])->default('belum_bayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
