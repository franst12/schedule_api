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
        $tasks = Task::with('course')
            ->where('user_id', Auth::id())
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json($tasks);
    }

    public function taskPending()
    {
        $tasks = Task::with('course')
            ->where('user_id', Auth::id())
            ->where('is_finished', false)
            ->orderBy('deadline', 'asc')
            ->get();

        return response()->json($tasks);
    }

    public function taskDone()
    {
        $tasks = Task::with('course')
            ->where('user_id', Auth::id())
            ->where('is_finished', true)
            ->orderBy('deadline', 'desc')
            ->get();

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'task_title' => 'required|string|max:255',
            'description' => 'nullable|string',
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

        $validator = Validator::make($request->all(), [
            'course_id' => 'exists:courses,id',
            'task_title' => 'string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'date',
            'is_finished' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task->update($request->all());

        return response()->json([
            'message' => 'Tugas berhasil diperbarui',
            'data' => $task->load('course')
        ]);
    }

    public function markAsFinished($id)
    {
        $task = Task::where('user_id', Auth::id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $task->update(['is_finished' => true]);

        return response()->json([
            'message' => 'Tugas ditandai sebagai selesai',
            'data' => $task->load('course')
        ]);
    }

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