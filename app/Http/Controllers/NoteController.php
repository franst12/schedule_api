<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $note = Note::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'title' => $request->title,
            'content' => $request->input('content')
        ]);

        return response()->json([
            'message' => 'Catatan berhasil dibuat',
            'data' => $note->load('course')
        ], 201);
    }

    public function index()
    {
        $notes = Note::with('course')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($notes);
    }

    public function show($id)
    {
        $note = Note::with('course')
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

        $validator = Validator::make($request->all(), [
            'course_id' => 'exists:courses,id',
            'title' => 'string|max:255',
            'content' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $note->update($request->all());

        return response()->json([
            'message' => 'Catatan berhasil diperbarui',
            'data' => $note->load('course')
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