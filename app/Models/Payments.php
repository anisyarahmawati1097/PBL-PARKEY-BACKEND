<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'payment_string',
        'status',
        'parkirs_id'
    ];

    public function parkir()
    {
        return $this->belongsTo(Parkir::class, 'parkirs_id');
    }
}
