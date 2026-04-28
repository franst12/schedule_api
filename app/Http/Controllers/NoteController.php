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
        $notes = Note::with('schedule.course')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($notes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'note_title' => 'required|string|max:255',
            'note_description' => 'nullable|string',
            'note_link' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $note = Note::create([
            'user_id' => Auth::id(),
            'schedule_id' => $request->schedule_id,
            'note_title' => $request->note_title,
            'note_description' => $request->note_description,
            'note_link' => $request->note_link,
        ]);

        return response()->json([
            'message' => 'Catatan berhasil dibuat',
            'data' => $note->load('schedule.course')
        ], 201);
    }

    public function show($id)
    {
        $note = Note::with('schedule.course')
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
            'data' => $note->load('schedule.course')
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