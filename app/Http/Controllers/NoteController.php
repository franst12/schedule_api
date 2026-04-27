<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::with('jadwalKuliah.mataKuliah')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($notes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_kuliah_id' => 'required|exists:jadwal_kuliahs,id',
            'judul_catatan' => 'required|string|max:255',
            'isi_catatan' => 'required|string',
            'link_materi' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $note = Note::create([
            'user_id' => Auth::id(),
            'jadwal_kuliah_id' => $request->jadwal_kuliah_id,
            'judul_catatan' => $request->judul_catatan,
            'isi_catatan' => $request->isi_catatan,
            'link_materi' => $request->link_materi,
        ]);

        return response()->json([
            'message' => 'Catatan berhasil dibuat',
            'data' => $note->load('jadwalKuliah.mataKuliah')
        ], 201);
    }

    public function show($id)
    {
        $note = Note::with('jadwalKuliah.mataKuliah')
            ->where('user_id', Auth::id())
            ->find($id);

        if (!$note) {
            return response()->json(['message' => 'Catatan tidak ditemukan'], 404);
        }

        return response()->json($note);
    }

    public function update(Request $request, $id)
    {
        $note = Note::where('user_id', Auth::id())->find($id);

        if (!$note) {
            return response()->json(['message' => 'Catatan tidak ditemukan'], 404);
        }

        $note->update($request->all());

        return response()->json([
            'message' => 'Catatan berhasil diperbarui',
            'data' => $note->load('jadwalKuliah.mataKuliah')
        ]);
    }

    public function destroy($id)
    {
        $note = Note::where('user_id', Auth::id())->find($id);

        if (!$note) {
            return response()->json(['message' => 'Catatan tidak ditemukan'], 404);
        }

        $note->delete();

        return response()->json(['message' => 'Catatan berhasil dihapus']);
    }
}