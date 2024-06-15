<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penjual extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_telp',
        'alamat',
        'foto',
        'ewallet_id',
        'nama_profil_ewallet',
        'nomor_ewallet',
        'is_verified',
    ];

    /**
     * Get the user that owns the Penjual
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the ewallet associated with the Penjual
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ewallet(): HasOne
    {
        return $this->hasOne(Ewallet::class, 'ewallet_id', 'id');
    }

    /**
     * Get all of the akunGame for the Penjual
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function akunGame(): HasMany
    {
        return $this->hasMany(AkunGame::class, 'penjual_id', 'id');
    }

    /**
     * Get all of the transaksi for the Penjual
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'penjual_id', 'id');
    }
}
