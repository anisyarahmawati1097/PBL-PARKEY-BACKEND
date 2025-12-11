<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slot;

class SlotSeeder extends Seeder
{
    public function run(): void
    {

        Slot::create([
            'id_lokasi' => 1,
            'slot' => 15, // jumlah slot
            'status' => 'available',
        ]);


        Slot::create([
            'id_lokasi' => 2,
            'slot' => 20, // jumlah slot
            'status' => 'available',
        ]);
    }
}
