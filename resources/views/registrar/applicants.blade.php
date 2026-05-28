@extends('layouts.app')
@section('title', 'Applicants')

@section('content')
@php
    $rejectedCount = \App\Models\Applicant::where('status', 'rejected')->count();
@endphp
<div class="flex items-start justify-between flex-wrap gap-3">
    <h1 class="text-2xl font-semibold">Applicants</h1>
    <div class="flex items-center gap-2">
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.trash.index') }}"
                   class="inline-flex items-center gap-2 bg-rose-600 text-white px-4 py-2 rounded hover:bg-rose-700 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/></svg>
                    Rejected
                    @if($rejectedCount > 0)
                        <span class="bg-white/25 px-2 rounded-full text-xs">{{ $rejectedCount }}</span>
                    @endif
                </a>
            @else
                <a href="{{ route('registrar.applicants.index', ['status' => 'rejected']) }}"
                   class="inline-flex items-center gap-2 bg-rose-600 text-white px-4 py-2 rounded hover:bg-rose-700 text-sm">
                    Rejected
                    @if($rejectedCount > 0)
                        <span class="bg-white/25 px-2 rounded-full text-xs">{{ $rejectedCount }}</span>
                    @endif
                </a>
            @endif
        @endauth
        <a href="{{ route('registrar.applicants.export', request()->query()) }}"
           class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 text-sm">
            Export CSV
        </a>
    </div>
</div>

@php
    $pendingCount = \App\Models\Applicant::where('status', 'pre_registered')->count();
@endphp
@if($pendingCount > 0 && ($filter['status'] ?? '') !== 'pre_registered')
    <a href="{{ route('registrar.applicants.index', ['status' => 'pre_registered']) }}"
       class="mt-4 inline-flex items-center gap-2 rounded border border-amber-300 bg-amber-50 text-amber-900 px-3 py-2 text-sm hover:bg-amber-100">
        <span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span>
        <strong>{{ $pendingCount }}</strong> pre-registration form(s) awaiting your approval — review now →
    </a>
@endif

@error('delete')
    <div class="mt-4 rounded bg-rose-50 border border-rose-200 text-rose-800 px-3 py-2 text-sm">{{ $message }}</div>
@enderror

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
                        <div class="flex items-center gap-3">
                            @if($a->status === 'pre_registered')
                                <a href="{{ route('registrar.verify.show', $a) }}"
                                   class="inline-block bg-amber-600 text-white px-3 py-1 rounded hover:bg-amber-700 text-xs">
                                    Review &amp; Approve →
                                </a>
                            @else
                                <a href="{{ route('registrar.verify.show', $a) }}" class="text-blue-600 hover:underline text-xs">Open →</a>
                            @endif

                            @if($a->status !== \App\Models\Applicant::STATUS_ENROLLED)
                                <form method="POST" action="{{ route('registrar.applicants.destroy', $a) }}"
                                      onsubmit="return confirm('Delete applicant {{ addslashes($a->reference_code) }} permanently?')">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-600 hover:underline text-xs">Delete</button>
                                </form>
                            @endif
                        </div>
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
