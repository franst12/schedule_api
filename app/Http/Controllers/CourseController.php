<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('user_id', Auth::id())->get();
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_name' => 'required|string|max:255',
            'course_code' => 'nullable|string',
            'sks' => 'nullable|integer',
            'credits' => 'nullable|integer',
            'lecturer_name' => 'nullable|string',
            'room' => 'nullable|string',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'color_hex' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();

        $course = Course::create($data);

        return response()->json([
            'message' => 'Course created successfully',
            'data' => $course
        ], 201);
    }

    public function show($id)
    {
        $course = Course::where('user_id', Auth::id())->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json($course);
    }

    public function update(Request $request, $id)
    {
        $course = Course::where('user_id', Auth::id())->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'course_name' => 'string|max:255',
            'sks' => 'integer',
            'credits' => 'integer',
            'lecturer_name' => 'string',
            'room' => 'string',
            'day_of_week' => 'string',
            'color_hex' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $course->update($request->all());

        return response()->json([
            'message' => 'Course updated successfully',
            'data' => $course
        ]);
    }

    public function destroy($id)
    {
        $course = Course::where('user_id', Auth::id())->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }
}