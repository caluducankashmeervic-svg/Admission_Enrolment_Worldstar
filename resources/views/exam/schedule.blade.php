@extends('layouts.app')
@section('title', 'Exam Schedules')

@section('content')
<h1 class="text-2xl font-semibold">Exam Schedules</h1>

<div class="grid lg:grid-cols-3 gap-5 mt-5">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-2">Batch</th>
                    <th class="px-4 py-2">When</th>
                    <th class="px-4 py-2">Venue</th>
                    <th class="px-4 py-2">Assigned</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $s)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-2 font-mono">{{ $s->batch_code }}</td>
                        <td class="px-4 py-2">{{ $s->exam_datetime->format('M d, Y g:i A') }}</td>
                        <td class="px-4 py-2">{{ $s->venue }}</td>
                        <td class="px-4 py-2">{{ $s->assigned_count }}/{{ $s->capacity }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('exam.results.index', $s) }}" class="text-blue-600 hover:underline">Results →</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">No schedules yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $schedules->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">New Batch</h2>
        <form method="POST" action="{{ route('exam.schedule.store') }}" class="space-y-3">
            @csrf
            <select name="academic_term_id" required class="w-full border rounded px-3 py-2">
                <option value="">Term…</option>
                @foreach($terms as $t)
                    <option value="{{ $t->id }}">{{ $t->school_year }} {{ $t->semester }}</option>
                @endforeach
            </select>
            <input name="batch_code" placeholder="Batch code e.g. BATCH-2026-01" required class="w-full border rounded px-3 py-2">
            <input type="datetime-local" name="exam_datetime" required class="w-full border rounded px-3 py-2">
            <input name="venue" placeholder="Venue" required class="w-full border rounded px-3 py-2">
            <input type="number" name="capacity" value="100" min="1" max="1000" class="w-full border rounded px-3 py-2">
            <textarea name="remarks" rows="2" placeholder="Remarks" class="w-full border rounded px-3 py-2"></textarea>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Create Batch</button>
        </form>
    </div>
</div>

@if($schedules->count() && $pending->count())
<div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5 mt-6">
    <h2 class="font-semibold mb-3">Assign Pending Applicants to Batch</h2>
    <p class="text-sm text-slate-500 mb-3">{{ $pending->count() }} pre-registered applicant(s) ready for assignment.</p>

    @foreach($schedules as $s)
        @if($s->remainingSlots() > 0)
            <details class="border border-slate-200 rounded mb-2">
                <summary class="px-3 py-2 cursor-pointer bg-slate-50">
                    <span class="font-mono">{{ $s->batch_code }}</span>
                    — remaining {{ $s->remainingSlots() }} slots
                </summary>
                <form method="POST" action="{{ route('exam.schedule.assign', $s) }}" class="p-3">
                    @csrf
                    <div class="max-h-60 overflow-y-auto border rounded divide-y divide-slate-100">
                        @foreach($pending as $p)
                            <label class="flex items-center gap-2 px-3 py-1.5 text-sm hover:bg-slate-50">
                                <input type="checkbox" name="applicant_ids[]" value="{{ $p->id }}">
                                <span class="font-mono text-xs text-slate-500">{{ $p->reference_code }}</span>
                                <span>{{ $p->full_name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button class="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Assign Selected
                    </button>
                </form>
            </details>
        @endif
    @endforeach
</div>
@endif
@endsection
