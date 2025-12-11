<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ambil admin + lokasi
        $admin = Admin::with('lokasi')->where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect'],
            ]);
        }

        // Buat token
        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Masuk berhasil',
            'data' => [
                'admin' => [
                    'id_admin'      => $admin->id_admin,
                    'username'      => $admin->username,
                    'nama_admin'    => $admin->nama_admin,

                    // === WAJIB SAMA PERSIS DENGAN FLUTTER ===
                     'id_lokasi'     => $admin->id_lokasi,
                    'nama_lokasi'   => $admin->lokasi?->nama_lokasi,
                    'alamat_lokasi' => $admin->lokasi?->alamat_lokasi,
                ],
                'token' => $token,
            ],
        ], 200);
    }
}
