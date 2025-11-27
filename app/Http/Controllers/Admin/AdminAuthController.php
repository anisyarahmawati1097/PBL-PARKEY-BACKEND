<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Login Admin
     */
    public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // Ambil admin berdasarkan username
    $admin = Admin::where('username', $request->username)->first();

    if (!$admin || !Hash::check($request->password, $admin->password)) {
        throw ValidationException::withMessages([
            'username' => ['The provided credentials are incorrect'],
        ]);
    }

    // Buat token sanctum
    $token = $admin->createToken('admin-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'data' => [
            'admin' => [
                'id_admin' => $admin->id_admin,
                'username' => $admin->username,
                'nama_admin' => $admin->nama_admin,
            ],
            'token' => $token,
        ],
    ], 200);
}
}