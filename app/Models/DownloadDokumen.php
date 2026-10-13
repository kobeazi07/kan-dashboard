<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DownloadDokumen extends Model
{
    use HasFactory;
    protected $table = 'download_dokumen_tables';
    protected $guarded = [];
}
