@extends('layouts.app')
@section('title', 'Sections')

@section('content')
<h1 class="text-2xl font-semibold">Sections</h1>

<div class="grid lg:grid-cols-3 gap-5 mt-5">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-2">Section</th>
                    <th class="px-4 py-2">Course</th>
                    <th class="px-4 py-2">Term</th>
                    <th class="px-4 py-2">YL</th>
                    <th class="px-4 py-2">Enrolled / Cap</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sections as $s)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-2 font-mono">{{ $s->name }}</td>
                        <td class="px-4 py-2">{{ $s->course->code }}</td>
                        <td class="px-4 py-2">{{ $s->academicTerm->school_year }} {{ $s->academicTerm->semester }}</td>
                        <td class="px-4 py-2">{{ $s->year_level }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-2">
                                <span>{{ $s->enrolled_count }}/{{ $s->capacity }}</span>
                                <div class="h-2 w-24 bg-slate-200 rounded overflow-hidden">
                                    <div class="h-2 bg-blue-500" style="width: {{ $s->utilizationPercent() }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            @if($s->is_open)
                                <span class="text-emerald-700 bg-emerald-100 rounded px-2 py-0.5 text-xs">Open</span>
                            @else
                                <span class="text-slate-600 bg-slate-100 rounded px-2 py-0.5 text-xs">Closed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No sections yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $sections->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Add Section</h2>
        <form method="POST" action="{{ route('admin.sections.store') }}" class="space-y-3">
            @csrf
            <select name="course_id" required class="w-full border rounded px-3 py-2">
                <option value="">Course…</option>
                @foreach($courses as $c)
                    <option value="{{ $c->id }}">{{ $c->code }}</option>
                @endforeach
            </select>
            <select name="academic_term_id" required class="w-full border rounded px-3 py-2">
                <option value="">Term…</option>
                @foreach($terms as $t)
                    <option value="{{ $t->id }}">{{ $t->school_year }} {{ $t->semester }}</option>
                @endforeach
            </select>
            <input name="name" placeholder="Section name e.g. BSIT-1A" required class="w-full border rounded px-3 py-2">
            <div class="grid grid-cols-2 gap-3">
                <input type="number" name="year_level" value="1" min="1" max="8" class="w-full border rounded px-3 py-2">
                <input type="number" name="capacity" value="{{ config('enrollment.default_section_capacity', 40) }}" min="1" max="200" class="w-full border rounded px-3 py-2">
            </div>
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="is_open" value="1" checked class="mr-2"> Open
            </label>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Create</button>
        </form>
    </div>
</div>
@endsection
