<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        return response()->json(Auth::user());
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'nim' => 'string|max:20',
            'major' => 'string|max:255',
            'semester' => 'integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data = $request->only(['name', 'nim', 'major', 'semester']);

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }
            $fotoPath = $request->file('foto')->store('profile_photos', 'public');
            $data['foto'] = $fotoPath;
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'data' => $user
        ]);
    }

    public function destroy()
    {
        $user = Auth::user();

        if ($user->foto) {
            Storage::delete('public/' . $user->foto);
        }

        $user->delete();

        return response()->json(['message' => 'Akun berhasil dihapus']);
    }
}