<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payments;
use App\Models\Slot;

class Parkir extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_lokasi',
        'masuk',
        'keluar',
        'parkir_id',
        'kendaraans_id'
    ];

    protected $casts = [
        'masuk'  => 'datetime',
        'keluar' => 'datetime',
    ];

    public function kendaraans()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraans_id');
    }

    public function payment()
    {
        return $this->hasOne(Payments::class, 'parkirs_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }

    public function slot()
{
    return $this->belongsTo(Slot::class, 'id_slot');
}

}
