<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // REGISTER
    public function daftar(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|string|email|unique:users,email',
            'tanggal_lahir' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'fullname' => $request->fullname,
            'name' => $request->name, // username
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user
        ], 201);
    }

    // LOGIN
    public function masuk(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau kata sandi salah'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'name' => $user->name, // username
                'email' => $user->email,
                'phone' => $user->phone,
                'tanggal_lahir' => $user->tanggal_lahir,
            ]
        ], 200);
    }

    // LOGOUT
    public function keluar(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil keluar']);
    }

    // UPDATE PROFILE
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255|unique:users,name,' . $user->id,
            'email' => 'nullable|string|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
        ]);

        $user->update([
            'fullname' => $request->fullname ?? $user->fullname,
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'phone' => $request->phone ?? $user->phone,
            'tanggal_lahir' => $request->tanggal_lahir ?? $user->tanggal_lahir,
        ]);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ]);
    }
}
