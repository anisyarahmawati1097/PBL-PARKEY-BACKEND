<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Laporan;

class DashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'pengendara' => User::count(),
            'lokasi' => Lokasi::count(),
            'laporan' => Laporan::count(),
        ]);
    }
}
