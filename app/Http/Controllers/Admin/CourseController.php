<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return view('admin.courses', [
            'courses' => Course::orderBy('code')->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'           => ['required', 'string', 'max:20', 'unique:courses,code'],
            'name'           => ['required', 'string', 'max:150'],
            'department'     => ['nullable', 'string', 'max:100'],
            'quota'          => ['required', 'integer', 'min:0'],
            'duration_years' => ['required', 'integer', 'min:1', 'max:8'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);
        $course = Course::create($data);
        AuditLog::record('course.create', $course);
        return back()->with('status', "Course {$course->code} created.");
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'department'     => ['nullable', 'string', 'max:100'],
            'quota'          => ['required', 'integer', 'min:0'],
            'duration_years' => ['required', 'integer', 'min:1', 'max:8'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);
        $course->update($data);
        AuditLog::record('course.update', $course);
        return back()->with('status', "Course {$course->code} updated.");
    }
}
