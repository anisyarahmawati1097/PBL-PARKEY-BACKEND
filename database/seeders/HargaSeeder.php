<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('harga')->insert([
            // Lokasi 1
            [
                'id_lokasi' => 1,
                'jenis_kendaraan' => 'motor',
                'harga' => 2000,
                'tambahan_per_jam' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lokasi' => 1,
                'jenis_kendaraan' => 'mobil',
                'harga' => 4000,
                'tambahan_per_jam' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lokasi' => 1,
                'jenis_kendaraan' => 'pickup',
                'harga' => 6000,
                'tambahan_per_jam' => 3000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lokasi' => 1,
                'jenis_kendaraan' => 'truck',
                'harga' => 8000,
                'tambahan_per_jam' => 4000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Lokasi 2
            [
                'id_lokasi' => 2,
                'jenis_kendaraan' => 'motor',
                'harga' => 1000,
                'tambahan_per_jam' => 1000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lokasi' => 2,
                'jenis_kendaraan' => 'mobil',
                'harga' => 3000,
                'tambahan_per_jam' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lokasi' => 2,
                'jenis_kendaraan' => 'pickup',
                'harga' => 5000,
                'tambahan_per_jam' => 3000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lokasi' => 2,
                'jenis_kendaraan' => 'truck',
                'harga' => 7000,
                'tambahan_per_jam' => 4000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
