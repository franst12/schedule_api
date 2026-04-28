<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function scheduleToday()
    {
        $today = Carbon::now()->format('l');
        $jadwal = Schedule::with('course')
            ->where('user_id', Auth::id())
            ->where('day', $today)
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json($jadwal);
    }

    public function index()
    {
        $jadwal = Schedule::with('course')
            ->where('user_id', Auth::id())
            ->orderBy('day', 'asc')
            ->get();

        return response()->json($jadwal);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $jadwal = Schedule::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'room' => $request->room,
        ]);

        return response()->json([
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $jadwal->load('course')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $jadwal = Schedule::where('user_id', Auth::id())->find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $jadwal->update($request->all());

        return response()->json([
            'message' => 'Jadwal berhasil diupdate',
            'data' => $jadwal->load('course')
        ]);
    }

    public function destroy($id)
    {
        $jadwal = Schedule::where('user_id', Auth::id())->find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $jadwal->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus']);
    }
}