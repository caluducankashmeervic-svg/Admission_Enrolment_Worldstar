<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\AuditLog;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        return view('admin.sections', [
            'sections' => Section::with('course', 'academicTerm')->orderBy('name')->paginate(20),
            'courses'  => Course::where('is_active', true)->orderBy('code')->get(),
            'terms'    => AcademicTerm::orderBy('school_year', 'desc')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id'        => ['required', 'exists:courses,id'],
            'academic_term_id' => ['required', 'exists:academic_terms,id'],
            'name'             => ['required', 'string', 'max:50'],
            'year_level'       => ['required', 'integer', 'min:1', 'max:8'],
            'capacity'         => ['required', 'integer', 'min:1', 'max:200'],
            'is_open'          => ['sometimes', 'boolean'],
        ]);
        $section = Section::create($data);
        AuditLog::record('section.create', $section);
        return back()->with('status', "Section {$section->name} created.");
    }

    public function update(Request $request, Section $section)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:50'],
            'year_level' => ['required', 'integer', 'min:1', 'max:8'],
            'capacity'   => ['required', 'integer', 'min:1', 'max:200'],
            'is_open'    => ['sometimes', 'boolean'],
        ]);
        $data['is_open'] = $request->boolean('is_open');
        $section->update($data);
        AuditLog::record('section.update', $section);
        return back()->with('status', "Section {$section->name} updated.");
    }

    public function destroy(Section $section)
    {
        AuditLog::record('section.delete', $section);
        $name = $section->name;
        $section->delete();
        return back()->with('status', "Section {$name} deleted.");
    }
}
