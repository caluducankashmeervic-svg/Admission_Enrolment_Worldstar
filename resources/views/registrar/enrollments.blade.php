@extends('layouts.app')
@section('title', 'Enrollments')

@section('content')
<div class="flex items-start justify-between flex-wrap gap-3">
    <h1 class="text-2xl font-semibold">Enrollments</h1>
    <a href="{{ route('registrar.enrollments.export', request()->query()) }}"
       class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 text-sm">
        Export CSV
    </a>
</div>

<form method="GET" class="mt-4 grid md:grid-cols-5 gap-2">
    <input name="q" value="{{ $filter['q'] ?? '' }}" placeholder="Enrollment no / student"
           class="border rounded px-3 py-2 md:col-span-2">
    <select name="course_id" class="border rounded px-3 py-2">
        <option value="">All courses</option>
        @foreach($courses as $c)
            <option value="{{ $c->id }}" @selected((int)($filter['course_id'] ?? 0)===$c->id)>{{ $c->code }}</option>
        @endforeach
    </select>
    <select name="academic_term_id" class="border rounded px-3 py-2">
        <option value="">All terms</option>
        @foreach($terms as $t)
            <option value="{{ $t->id }}" @selected((int)($filter['academic_term_id'] ?? 0)===$t->id)>
                {{ $t->school_year }} {{ $t->semester }}
            </option>
        @endforeach
    </select>
    <div class="flex gap-2">
        <button class="bg-slate-700 text-white px-4 py-2 rounded flex-1">Filter</button>
        <a href="{{ route('registrar.enrollments.index') }}" class="px-3 py-2 text-slate-600 hover:underline">Reset</a>
    </div>
</form>

<div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-x-auto mt-5">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-600">
            <tr>
                <th class="px-3 py-2">Enrollment No</th>
                <th class="px-3 py-2">Student</th>
                <th class="px-3 py-2">Course</th>
                <th class="px-3 py-2">Section</th>
                <th class="px-3 py-2">Term</th>
                <th class="px-3 py-2">Enrolled At</th>
                <th class="px-3 py-2">Processed By</th>
                <th class="px-3 py-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($enrollments as $e)
                <tr class="border-t border-slate-100 hover:bg-slate-50">
                    <td class="px-3 py-2 font-mono text-xs">{{ $e->enrollment_no }}</td>
                    <td class="px-3 py-2">{{ $e->applicant?->full_name }}
                        <div class="text-xs text-slate-400 font-mono">{{ $e->applicant?->reference_code }}</div></td>
                    <td class="px-3 py-2">{{ $e->course?->code ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $e->section?->name ?? '—' }}</td>
                    <td class="px-3 py-2 text-xs">{{ $e->academicTerm?->school_year }} {{ $e->academicTerm?->semester }}</td>
                    <td class="px-3 py-2 text-xs">{{ optional($e->enrolled_at)->format('M d, Y H:i') }}</td>
                    <td class="px-3 py-2 text-xs">{{ $e->processor?->name }}</td>
                    <td class="px-3 py-2">
                        <span class="text-xs text-slate-400">—</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-3 py-8 text-center text-slate-500">No enrollments match.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3">{{ $enrollments->links() }}</div>
</div>
@endsection
