<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ewallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'icon',
    ];

    /**
     * Get the penjual that owns the Ewallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penjual(): BelongsTo
    {
        return $this->belongsTo(Penjual::class, 'ewallet_id', 'id');
    }
}
