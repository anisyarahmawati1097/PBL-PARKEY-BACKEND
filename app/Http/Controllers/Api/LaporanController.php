<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;

class LaporanController extends Controller
{
    public function index()
    {
        return Laporan::latest()->get();
    }
}
