<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokasiSeeder extends Seeder
{
    public function run()
    {
        DB::table('lokasis')->insert([
            [
                'nama_lokasi'   => 'GRAND BATAM MALL',
                'alamat_lokasi' => 'Jl. Pembangunan, Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau, Indonesia',
            ],
            [
                'nama_lokasi'   => 'SNL FOOD TANJUNG UMA',
                'alamat_lokasi' => 'Jl. Tanjung Uma, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau, Indonesia',
            ],
        ]);
    }
}
