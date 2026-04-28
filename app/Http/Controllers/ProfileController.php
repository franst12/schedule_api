<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return response()->json(Auth::user());
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'major' => 'nullable|string|max:255', // Tambahkan ini
            'semester' => 'nullable|integer',      // Tambahkan ini
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user->name = $request->name;
        $user->nim = $request->nim;
        $user->major = $request->major;       // Update jurusan
        $user->semester = $request->semester; // Update semester

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }

            // Simpan ke folder 'profile_photos' biar seragam dengan register
            $path = $request->file('foto')->store('profile_photos', 'public');
            $user->foto = $path;
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
            'foto_url' => $user->foto ? asset('storage/' . $user->foto) : null // Tambahkan URL biar Flutter gampang panggil
        ]);
    }
}