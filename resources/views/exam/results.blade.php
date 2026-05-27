@extends('layouts.app')
@section('title', 'Exam Results — ' . $schedule->batch_code)

@section('content')
<div class="flex items-start justify-between">
    <div>
        <h1 class="text-2xl font-semibold">Results — {{ $schedule->batch_code }}</h1>
        <p class="text-sm text-slate-500">
            {{ $schedule->exam_datetime->format('M d, Y g:i A') }} · {{ $schedule->venue }}
        </p>
    </div>
</div>

<form method="POST" action="{{ route('exam.results.post', $schedule) }}" class="mt-5">
    @csrf
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <label class="block mb-4">
            <span class="text-sm font-medium">Passing Score</span>
            <input type="number" name="passing_score" value="{{ config('enrollment.default_passing_score', 75) }}"
                   min="0" max="100" step="0.01" required
                   class="mt-1 w-40 border rounded px-3 py-2">
        </label>
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-600">
                <tr>
                    <th class="py-2">Ref Code</th>
                    <th class="py-2">Applicant</th>
                    <th class="py-2">Score</th>
                    <th class="py-2">Result</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $r)
                    <tr class="border-t border-slate-100">
                        <td class="py-2 font-mono text-xs">{{ $r->applicant->reference_code }}</td>
                        <td class="py-2">{{ $r->applicant->full_name }}</td>
                        <td class="py-2">
                            <input type="hidden" name="results[{{ $loop->index }}][id]" value="{{ $r->id }}">
                            <input type="number" step="0.01" min="0" max="100"
                                   name="results[{{ $loop->index }}][score]"
                                   value="{{ $r->score }}"
                                   class="w-24 border rounded px-2 py-1">
                        </td>
                        <td class="py-2">
                            @php $cls = ['pending'=>'slate','passed'=>'emerald','failed'=>'rose'][$r->result] ?? 'slate'; @endphp
                            <span class="text-{{ $cls }}-700 bg-{{ $cls }}-100 rounded px-2 py-0.5 text-xs capitalize">
                                {{ $r->result }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-6 text-center text-slate-500">No applicants assigned yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>

        @if($results->count())
            <button class="mt-4 bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                Save Scores
            </button>
        @endif
    </div>
</form>

<a href="{{ route('exam.schedule.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Back to schedules</a>
@endsection
