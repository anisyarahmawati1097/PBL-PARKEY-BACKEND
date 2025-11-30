<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentsFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'payment_string',
        'status',
        'parkirs_id',
    ];

    public function parkirs()
    {
        return $this->belongsTo(Parkir::class);
    }
}
