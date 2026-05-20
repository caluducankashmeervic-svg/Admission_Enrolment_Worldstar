@extends('layouts.app')
@section('title', 'Applicants')

@section('content')
<div class="flex items-start justify-between flex-wrap gap-3">
    <h1 class="text-2xl font-semibold">Applicants</h1>
    <a href="{{ route('registrar.applicants.export', request()->query()) }}"
       class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 text-sm">
        Export CSV
    </a>
</div>

<form method="GET" class="mt-4 grid md:grid-cols-5 gap-2">
    <input name="q" value="{{ $filter['q'] ?? '' }}" placeholder="Ref / name / mobile" class="border rounded px-3 py-2">
    <select name="status" class="border rounded px-3 py-2">
        <option value="">All statuses</option>
        @foreach($statuses as $s)
            <option value="{{ $s }}" @selected(($filter['status'] ?? '')===$s)>{{ str_replace('_', ' ', $s) }}</option>
        @endforeach
    </select>
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
        <a href="{{ route('registrar.applicants.index') }}" class="px-3 py-2 text-slate-600 hover:underline">Reset</a>
    </div>
</form>

<div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-x-auto mt-5">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-600">
            <tr>
                <th class="px-3 py-2">Ref</th>
                <th class="px-3 py-2">Name</th>
                <th class="px-3 py-2">Course</th>
                <th class="px-3 py-2">Term</th>
                <th class="px-3 py-2">Mobile</th>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2">Exam</th>
                <th class="px-3 py-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($applicants as $a)
                <tr class="border-t border-slate-100 hover:bg-slate-50">
                    <td class="px-3 py-2 font-mono text-xs">{{ $a->reference_code }}</td>
                    <td class="px-3 py-2">{{ $a->full_name }}</td>
                    <td class="px-3 py-2">{{ $a->preferredCourse?->code }}</td>
                    <td class="px-3 py-2 text-xs">{{ $a->academicTerm?->school_year }} {{ $a->academicTerm?->semester }}</td>
                    <td class="px-3 py-2 text-xs">{{ $a->mobile }}</td>
                    <td class="px-3 py-2">
                        <span class="text-xs rounded px-2 py-0.5 capitalize
                            @class([
                                'bg-slate-100 text-slate-700'  => $a->status === 'pre_registered',
                                'bg-sky-100 text-sky-700'      => $a->status === 'exam_scheduled',
                                'bg-amber-100 text-amber-700'  => $a->status === 'exam_completed',
                                'bg-blue-100 text-blue-700'=> $a->status === 'verified',
                                'bg-emerald-100 text-emerald-700' => $a->status === 'enrolled',
                                'bg-rose-100 text-rose-700'    => $a->status === 'rejected',
                            ])">
                            {{ str_replace('_', ' ', $a->status) }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-xs">
                        @if($a->latestExamResult)
                            {{ $a->latestExamResult->score ?? '—' }} / {{ $a->latestExamResult->result }}
                        @else — @endif
                    </td>
                    <td class="px-3 py-2">
                        <a href="{{ route('registrar.verify.show', $a) }}" class="text-blue-600 hover:underline text-xs">Open →</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-3 py-8 text-center text-slate-500">No applicants match.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3">{{ $applicants->links() }}</div>
</div>
@endsection
