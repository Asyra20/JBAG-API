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
        Schema::create('akun_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjual_id');
            $table->foreignId('game_id');
            $table->string('judul', 255);
            $table->text('deskripsi');
            $table->string('gambar', 255);
            $table->integer('harga', unsigned: true);
            $table->enum('status_akun', ['tersedia', 'pending', 'terjual'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_games');
    }
};
