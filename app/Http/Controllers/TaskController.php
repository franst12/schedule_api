<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        // Tetap pakai with('course') biar di Flutter muncul nama matkulnya
        $tasks = Task::with('course')
            ->where('user_id', Auth::id())
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'task_title' => 'required|string|max:255',
            'description' => 'nullable|string', // Pastikan ini sama dengan $fillable
            'deadline' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task = Task::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'task_title' => $request->task_title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'is_finished' => false,
        ]);

        return response()->json([
            'message' => 'Tugas berhasil ditambahkan',
            'data' => $task->load('course')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        // Pakai $request->all() boleh, tapi pastikan input dari Postman/Flutter
        // sudah sesuai dengan nama kolom di $fillable
        $task->update($request->all());

        return response()->json([
            'message' => 'Tugas berhasil diperbarui',
            'data' => $task->load('course')
        ]);
    }

    // Fungsi Mark As Finished sudah oke ngab!
    public function markAsFinished($id)
    {
        $task = Task::where('user_id', Auth::id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $task->update(['is_finished' => true]);

        return response()->json([
            'message' => 'Tugas ditandai sebagai selesai',
            'data' => $task
        ]);
    }

    // Fungsi Destroy juga sudah mantap
    public function destroy($id)
    {
        $task = Task::where('user_id', Auth::id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $task->delete();
        return response()->json(['message' => 'Tugas berhasil dihapus']);
    }
}