<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlotSeeder extends Seeder
{
    public function run()
    {
        DB::table('slots')->insert([
            [
                'id_slot'   => 1,
                'id_lokasi' => 1,
                'slot'      => 25,
                'status'    => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_slot'   => 2,
                'id_lokasi' => 2,
                'slot'      => 15,
                'status'    => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
