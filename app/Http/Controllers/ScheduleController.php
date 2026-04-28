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

        $schedules = Schedule::with('course')
            ->where('user_id', Auth::id())
            ->whereRaw('LOWER(day) = ?', [strtolower($today)])
            ->get();

        return response()->json($schedules);
    }

    public function index()
    {
        $schedules = Schedule::with('course')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($schedules);
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

        $schedule = Schedule::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'room' => $request->room,
        ]);

        return response()->json([
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $schedule->load('course')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::where('user_id', Auth::id())->find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'course_id' => 'exists:courses,id',
            'day' => 'string',
            'start_time' => 'string',
            'end_time' => 'string',
            'room' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $schedule->update($request->all());

        return response()->json([
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $schedule->load('course')
        ]);
    }

    public function destroy($id)
    {
        $schedule = Schedule::where('user_id', Auth::id())->find($id);

        if (!$schedule) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $schedule->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus']);
    }
}