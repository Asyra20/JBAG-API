<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Keranjang extends Pivot
{

    protected $table = 'keranjang';

    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'akun_game_id',
    ];

    /**
     * Get the user that owns the Keranjang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pembeli(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the akunGame that owns the Keranjang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function akunGame(): BelongsTo
    {
        return $this->belongsTo(AkunGame::class, 'akun_game_id', 'id');
    }
}
