<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // 1. Registrasi Mahasiswa Baru
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'major' => 'nullable|string|max:255', // Tambahkan ini
            'semester' => 'nullable|integer',      // Tambahkan ini
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('profile_photos', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'foto' => $fotoPath,
            'major' => $request->major,       // Simpan ini
            'semester' => $request->semester, // Simpan ini
        ]);

        $token = Auth::login($user);
        return response()->json([
            'message' => 'User berhasil didaftarkan',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // 2. Login & Generate JWT
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = Auth::attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    // 3. Ambil Data Profil (Fitur No. 8)
    public function me()
    {
        return response()->json(auth::user());
    }

    // 4. Logout
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    // Helper untuk format response token
    protected function respondWithToken($token)
    {
        /** @var \PHPOpenSourceSaver\JWTAuth\Factory $factory */
        $factory = Auth::factory();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $factory->getTTL() * 60
        ]);
    }
}