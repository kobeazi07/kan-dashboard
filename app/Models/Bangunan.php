<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bangunan extends Model
{
    protected $table = 'bangunans';

    protected $fillable = [
        'kawasan_id',
        'nama',
        'deskripsi',
        'posisi_x',
        'posisi_y',
    ];

    public function kawasan(): BelongsTo
    {
        return $this->belongsTo(Kawasan::class);
    }

    public function fotoBangunan(): HasMany
    {
        return $this->hasMany(FotoBangunan::class);
    }
}
