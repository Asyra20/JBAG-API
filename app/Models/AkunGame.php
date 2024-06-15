<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AkunGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjual_id',
        'game_id',
        'judul',
        'deskripsi',
        'gambar',
        'harga',
        'status_akun',
    ];

    /**
     * Get the game that owns the AkunGame
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    /**
     * Get the penjual that owns the AkunGame
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penjual(): BelongsTo
    {
        return $this->belongsTo(Penjual::class, 'penjual_id', 'id');
    }

    /**
     * The pembeli that belong to the AkunGame
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pembeli(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'keranjang', 'akun_game_id', 'user_id')
            ->using(Keranjang::class)
            ->withTimestamps();
    }
}
