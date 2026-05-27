<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AcademicTermController extends Controller
{
    public function index()
    {
        return view('admin.terms', [
            'terms' => AcademicTerm::orderBy('school_year', 'desc')
                ->orderBy('semester')->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_year' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'semester'    => ['required', 'in:1st,2nd,Summer'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);
        $term = AcademicTerm::create($data);
        AuditLog::record('term.create', $term);
        return back()->with('status', "Term {$term->school_year} {$term->semester} created.");
    }

    public function update(Request $request, AcademicTerm $term)
    {
        $data = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
            'is_active'  => ['sometimes', 'boolean'],
        ]);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $term->update($data);
        AuditLog::record('term.update', $term);
        return back()->with('status', "Term {$term->school_year} {$term->semester} updated.");
    }

    public function activate(AcademicTerm $term)
    {
        AcademicTerm::query()->update(['is_active' => false]);
        $term->update(['is_active' => true]);
        AuditLog::record('term.activate', $term);
        return back()->with('status', "Activated {$term->school_year} {$term->semester}.");
    }

    public function destroy(AcademicTerm $term)
    {
        if ($term->sections()->exists() || $term->examSchedules()->exists() || $term->enrollments()->exists() || $term->applicants()->exists()) {
            return back()->withErrors([
                'term_delete' => "Cannot delete {$term->school_year} {$term->semester}. It is already in use.",
            ]);
        }

        $label = $term->school_year . ' ' . $term->semester;
        AuditLog::record('term.delete', $term);
        $term->delete();

        return back()->with('status', "Term {$label} deleted.");
    }
}
