@extends('layouts.app')
@section('title', 'Sections')

@section('content')
<h1 class="text-2xl font-semibold">Sections</h1>

@if(session('status'))
    <div class="mt-4 rounded border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-800">
        {{ session('status') }}
    </div>
@endif

<div class="grid lg:grid-cols-3 gap-5 mt-5">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-2">Section</th>
                    <th class="px-4 py-2">Course</th>
                    <th class="px-4 py-2">Term</th>
                    <th class="px-4 py-2">YL</th>
                    <th class="px-4 py-2">Enrolled / Cap</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($sections as $s)
                    {{-- Display row --}}
                    <tr class="border-t border-slate-100 hover:bg-slate-50" id="row-{{ $s->id }}">
                        <td class="px-4 py-2 font-mono">{{ $s->name }}</td>
                        <td class="px-4 py-2">{{ $s->course?->code ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $s->academicTerm?->school_year }} {{ $s->academicTerm?->semester }}</td>
                        <td class="px-4 py-2">{{ $s->year_level }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-2">
                                <span>{{ $s->enrolled_count }}/{{ $s->capacity }}</span>
                                <div class="h-2 w-24 bg-slate-200 rounded overflow-hidden">
                                    <div class="h-2 {{ $s->enrolled_count >= $s->capacity ? 'bg-rose-500' : 'bg-blue-500' }}"
                                         style="width: {{ $s->utilizationPercent() }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            @if($s->enrolled_count >= $s->capacity)
                                <span class="text-rose-700 bg-rose-100 rounded px-2 py-0.5 text-xs font-semibold">Full</span>
                            @elseif($s->is_open)
                                <span class="text-emerald-700 bg-emerald-100 rounded px-2 py-0.5 text-xs">Open</span>
                            @else
                                <span class="text-slate-600 bg-slate-100 rounded px-2 py-0.5 text-xs">Closed</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="toggleEdit({{ $s->id }})"
                                        class="text-blue-600 hover:underline text-xs">Edit</button>
                                <form method="POST" action="{{ route('admin.sections.destroy', $s) }}"
                                      onsubmit="return confirm('Delete section {{ addslashes($s->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-600 hover:underline text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {{-- Inline edit row (hidden by default) --}}
                    <tr id="edit-{{ $s->id }}" class="hidden border-t border-blue-100 bg-blue-50/40">
                        <td colspan="7" class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.sections.update', $s) }}" class="flex flex-wrap gap-3 items-end">
                                @csrf @method('PUT')
                                <div>
                                    <label class="text-xs text-slate-500 block mb-1">Name</label>
                                    <input name="name" value="{{ $s->name }}" required class="border rounded px-2 py-1 text-sm w-36">
                                </div>
                                <div>
                                    <label class="text-xs text-slate-500 block mb-1">Year Level</label>
                                    <input type="number" name="year_level" value="{{ $s->year_level }}" min="1" max="8" required class="border rounded px-2 py-1 text-sm w-20">
                                </div>
                                <div>
                                    <label class="text-xs text-slate-500 block mb-1">Capacity</label>
                                    <input type="number" name="capacity" value="{{ $s->capacity }}" min="1" max="200" required class="border rounded px-2 py-1 text-sm w-20">
                                </div>
                                <label class="inline-flex items-center text-sm gap-1 self-end pb-1">
                                    <input type="checkbox" name="is_open" value="1" @checked($s->is_open)> Open
                                </label>
                                <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm self-end hover:bg-blue-700">Save</button>
                                <button type="button" onclick="toggleEdit({{ $s->id }})" class="text-slate-500 hover:underline text-sm self-end">Cancel</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">No sections yet.</td></tr>
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

<script>
function toggleEdit(id) {
    const editRow = document.getElementById('edit-' + id);
    editRow.classList.toggle('hidden');
}
</script>
@endsection
