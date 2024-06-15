<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'icon',
    ];

    /**
     * Get all of the akunGame for the Game
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function akunGame(): HasMany
    {
        return $this->hasMany(AkunGame::class, 'game_id', 'id');
    }
}
