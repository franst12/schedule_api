<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with('mataKuliah')
            ->where('user_id', Auth::id())
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json($assignments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'judul_tugas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $assignment = Assignment::create([
            'user_id' => Auth::id(),
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'judul_tugas' => $request->judul_tugas,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
            'is_finished' => false,
        ]);

        return response()->json([
            'message' => 'Tugas berhasil ditambahkan',
            'data' => $assignment->load('mataKuliah')
        ], 201);
    }

    public function show($id)
    {
        $assignment = Assignment::with('mataKuliah')
            ->where('user_id', Auth::id())
            ->find($id);

        if (!$assignment) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        return response()->json($assignment);
    }

    public function update(Request $request, $id)
    {
        $assignment = Assignment::where('user_id', Auth::id())->find($id);

        if (!$assignment) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $assignment->update($request->all());

        return response()->json([
            'message' => 'Tugas berhasil diperbarui',
            'data' => $assignment->load('mataKuliah')
        ]);
    }

    public function markAsFinished($id)
    {
        $assignment = Assignment::where('user_id', Auth::id())->find($id);

        if (!$assignment) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $assignment->update(['is_finished' => true]);

        return response()->json([
            'message' => 'Tugas ditandai sebagai selesai',
            'data' => $assignment
        ]);
    }

    public function destroy($id)
    {
        $assignment = Assignment::where('user_id', Auth::id())->find($id);

        if (!$assignment) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $assignment->delete();

        return response()->json(['message' => 'Tugas berhasil dihapus']);
    }
}