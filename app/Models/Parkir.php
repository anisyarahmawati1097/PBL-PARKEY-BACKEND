<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parkir extends Model
{
    /** @use HasFactory<\Database\Factories\ParkirFactory> */
    use HasFactory;
    protected $fillable = ["masuk", "keluar", "kendaraans_id", "parkir_id", "harga"];
    protected $casts = ["masuk" => "datetime", "keluar" => "datetime"];

    protected $hidden = ["kendaraans_id"];
    public function kendaraans()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
