<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'akun_game_id',
        'uid_akun',
        'email_akun',
        'password_akun',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_akun',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password_akun' => 'hashed',
        ];
    }

    /**
     * Get the transaksi that owns the DetailTransaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'id');
    }

    /**
     * Get the akunGame that owns the DetailTransaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function akunGame(): BelongsTo
    {
        return $this->belongsTo(AkunGame::class, 'akun_game_id', 'id');
    }

    /**
     * Get the penilaian associated with the DetailTransaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function penilaian(): HasOne
    {
        return $this->hasOne(Penilaian::class, 'detail_transaksi_id', 'id');
    }
}
