<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MataKuliahController extends Controller
{
    // Tampilkan semua matkul milik user yang login
    public function index()
    {
        $matkul = MataKuliah::where('user_id', Auth::id())->get();
        return response()->json($matkul);
    }

    // Simpan matkul baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_matkul' => 'required|string',
            'kode_matkul' => 'required|string',
            'sks' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $matkul = MataKuliah::create([
            'user_id' => Auth::id(),
            'nama_matkul' => $request->nama_matkul,
            'kode_matkul' => $request->kode_matkul,
            'sks' => $request->sks,
        ]);

        return response()->json(['message' => 'Mata kuliah berhasil ditambah', 'data' => $matkul], 201);
    }

    // Update data matkul
    public function update(Request $request, $id)
    {
        $matkul = MataKuliah::where('user_id', Auth::id())->find($id);

        if (!$matkul) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $matkul->update($request->all());
        return response()->json(['message' => 'Mata kuliah berhasil diupdate', 'data' => $matkul]);
    }

    // Hapus matkul
    public function destroy($id)
    {
        $matkul = MataKuliah::where('user_id', Auth::id())->find($id);

        if (!$matkul) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $matkul->delete();
        return response()->json(['message' => 'Mata kuliah berhasil dihapus']);
    }
}