<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoBangunan extends Model
{
    protected $table = 'foto_bangunans';

    protected $fillable = [
        'bangunan_id',
        'foto',
        'keterangan',
    ];

    public function bangunan(): BelongsTo
    {
        return $this->belongsTo(Bangunan::class);
    }
}
