<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $fillable = [
        'plat_nomor',
        'jenis',
        'merk',
        'model',
        'tahun',
        'warna',
        'foto',
        'pemilik'
    ];
}
