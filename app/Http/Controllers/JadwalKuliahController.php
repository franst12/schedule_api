<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JadwalKuliahController extends Controller
{
    // Fitur No. 5: Dashboard Jadwal Hari Ini
    public function jadwalHariIni()
    {
        // Mengambil hari ini dalam bahasa Indonesia
        // 'l' menghasilkan nama hari lengkap (Monday, Tuesday, dst)
        $hariIni = \Carbon\Carbon::now()->locale('id')->dayName;
        $jadwal = JadwalKuliah::with('mataKuliah')
            ->where('user_id', Auth::id())
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return response()->json($jadwal);
    }

    // Fitur No. 4: Tampilkan Semua Jadwal
    public function index()
    {
        $jadwal = JadwalKuliah::with('mataKuliah')
            ->where('user_id', Auth::id())
            ->orderBy('hari', 'asc')
            ->get();

        return response()->json($jadwal);
    }

    // Fitur No. 4: Simpan Jadwal Baru (Create)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $jadwal = JadwalKuliah::create([
            'user_id' => Auth::id(),
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $request->ruangan,
        ]);

        return response()->json([
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $jadwal->load('mataKuliah')
        ], 201);
    }

    // Fitur No. 4: Update Jadwal
    public function update(Request $request, $id)
    {
        $jadwal = JadwalKuliah::where('user_id', Auth::id())->find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $jadwal->update($request->all());

        return response()->json([
            'message' => 'Jadwal berhasil diupdate',
            'data' => $jadwal->load('mataKuliah')
        ]);
    }

    // Fitur No. 4: Hapus Jadwal (Delete)
    public function destroy($id)
    {
        $jadwal = JadwalKuliah::where('user_id', Auth::id())->find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $jadwal->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus']);
    }
}