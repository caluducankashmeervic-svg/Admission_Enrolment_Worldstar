@extends('layouts.app')
@section('title', 'Exam Schedules')

@section('content')
<h1 class="text-2xl font-semibold">Exam Schedules</h1>
<p class="text-sm text-slate-500 mt-1">
    Applicants are auto-assigned to the earliest-scheduled open batch when a registrar approves their pre-registration form (FCFS).
</p>

@if($errors->any())
    <div class="mt-4 rounded bg-rose-50 border border-rose-200 text-rose-800 px-3 py-2 text-sm">
        @foreach($errors->all() as $err){{ $err }}<br>@endforeach
    </div>
@endif
@if(session('status'))
    <div class="mt-4 rounded bg-emerald-50 border border-emerald-200 text-emerald-800 px-3 py-2 text-sm">
        {{ session('status') }}
    </div>
@endif

<div class="grid lg:grid-cols-3 gap-5 mt-5">
    <div class="lg:col-span-2 space-y-3">
        @forelse($schedules as $s)
            <details class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                <summary class="cursor-pointer px-4 py-3 flex flex-wrap items-center gap-3 hover:bg-slate-50">
                    <span class="font-mono font-semibold text-blue-700">{{ $s->batch_code }}</span>
                    <span class="text-sm text-slate-600">{{ $s->exam_datetime->format('M d, Y g:i A') }}</span>
                    <span class="text-sm text-slate-500">{{ $s->venue }}</span>
                    <span class="ml-auto text-sm">
                        <span class="px-2 py-0.5 rounded {{ $s->remainingSlots() > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $s->assigned_count }}/{{ $s->capacity }}
                        </span>
                    </span>
                    <a href="{{ route('exam.results.index', $s) }}"
                       class="text-sm text-blue-600 hover:underline"
                       onclick="event.stopPropagation()">Results →</a>
                </summary>

                <div class="border-t border-slate-100 px-4 py-3">
                    @php $roster = $s->examResults; @endphp
                    @if($roster->isEmpty())
                        <p class="text-sm text-slate-500">No applicants assigned to this batch yet.</p>
                    @else
                        <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-left text-slate-500">
                                <tr>
                                    <th class="py-1 pr-2">Ref Code</th>
                                    <th class="py-1 pr-2">Name</th>
                                    <th class="py-1 pr-2">Program / Track</th>
                                    <th class="py-1 pr-2">Exam</th>
                                    <th class="py-1 pr-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roster as $r)
                                    @php
                                        $ap = $r->applicant;
                                    @endphp
                                    @if(! $ap) @continue @endif
                                    @php
                                        if ($ap->preferredCourse) {
                                            $prog = $ap->preferredCourse->code;
                                        } elseif ($ap->applicant_type === \App\Models\Applicant::TYPE_SHS) {
                                            $prog = $ap->strand_track ?: '—';
                                        } elseif ($ap->applicant_type === \App\Models\Applicant::TYPE_TESDA) {
                                            $prog = data_get($ap->profile_data, 'diploma_course')
                                                ?: data_get($ap->profile_data, 'course_qualification') ?: '—';
                                        } else {
                                            $prog = '—';
                                        }
                                    @endphp
                                    <tr class="border-t border-slate-100 align-top">
                                        <td class="py-1.5 pr-2 font-mono text-xs">{{ $ap->reference_code }}</td>
                                        <td class="py-1.5 pr-2">{{ $ap->full_name }}</td>
                                        <td class="py-1.5 pr-2">{{ $prog }}</td>
                                        <td class="py-1.5 pr-2 capitalize">{{ $r->result }}</td>
                                        <td class="py-1.5 pr-2 text-right">
                                            @if($r->result === \App\Models\ExamResult::RESULT_PENDING)
                                                <div class="flex flex-wrap justify-end gap-2">
                                                    <form method="POST"
                                                          action="{{ route('exam.schedule.applicant.remove', [$s, $ap]) }}"
                                                          class="inline">
                                                        @csrf
                                                        <button class="text-xs text-rose-700 hover:underline"
                                                                onclick="return confirm('Remove {{ $ap->reference_code }} from this batch? They will return to pre-registered.');">
                                                            Remove
                                                        </button>
                                                    </form>

                                                    <form method="POST"
                                                          action="{{ route('exam.schedule.applicant.move', [$s, $ap]) }}"
                                                          class="inline-flex items-center gap-1">
                                                        @csrf
                                                        <select name="target_schedule_id" required
                                                                class="text-xs border rounded px-1 py-0.5">
                                                            <option value="">Move to…</option>
                                                            @foreach($schedules as $other)
                                                                @if($other->id !== $s->id && $other->remainingSlots() > 0)
                                                                    <option value="{{ $other->id }}">{{ $other->batch_code }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <button class="text-xs text-blue-700 hover:underline">Move</button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-400">scored</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @endif
                </div>
            </details>
        @empty
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 text-center text-slate-500">
                No schedules yet.
            </div>
        @endforelse

        <div>{{ $schedules->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5 h-fit">
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
            <input type="datetime-local" name="exam_datetime" required
                   min="{{ now()->format('Y-m-d\TH:i') }}"
                   class="w-full border rounded px-3 py-2">
            <input name="venue" placeholder="Venue" required class="w-full border rounded px-3 py-2">
            <input type="number" name="capacity" value="100" min="1" max="1000" class="w-full border rounded px-3 py-2">
            <textarea name="remarks" rows="2" placeholder="Remarks" class="w-full border rounded px-3 py-2"></textarea>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Create Batch</button>
        </form>
    </div>
</div>
@endsection
