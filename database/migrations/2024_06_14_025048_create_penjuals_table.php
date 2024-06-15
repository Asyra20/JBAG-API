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
        Schema::create('penjuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('no_telp', 20);
            $table->text('alamat');
            $table->string('foto', 255)->nullable();
            $table->foreignId('ewallet_id');
            $table->string('nama_profil_ewallet', 20);
            $table->string('nomor_ewallet', 20);
            $table->enum('is_verified', ['true', 'false'])->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjuals');
    }
};
