<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kawasan extends Model
{
    protected $table = 'kawasans';
    protected $fillable = [
        'nama',
        'deskripsi',
        'gambar',
    ];

    public function bangunan(): HasMany
    {
        return $this->hasMany(Bangunan::class);
    }
}
