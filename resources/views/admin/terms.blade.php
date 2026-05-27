@extends('layouts.app')
@section('title', 'Academic Terms')

@section('content')
<h1 class="text-2xl font-semibold">Academic Terms</h1>

<div class="grid lg:grid-cols-3 gap-5 mt-5">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-2">School Year</th>
                    <th class="px-4 py-2">Sem</th>
                    <th class="px-4 py-2">Start</th>
                    <th class="px-4 py-2">End</th>
                    <th class="px-4 py-2">Active</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($terms as $t)
                    <tr class="border-t border-slate-100">
                        <form method="POST" action="{{ route('admin.terms.update', $t) }}">
                            @csrf @method('PUT')
                            <td class="px-4 py-2 font-mono">{{ $t->school_year }}</td>
                            <td class="px-4 py-2">{{ $t->semester }}</td>
                            <td class="px-4 py-2"><input type="date" name="start_date" value="{{ $t->start_date->format('Y-m-d') }}" class="border rounded px-2 py-1"></td>
                            <td class="px-4 py-2"><input type="date" name="end_date" value="{{ $t->end_date->format('Y-m-d') }}" class="border rounded px-2 py-1"></td>
                            <td class="px-4 py-2"><input type="checkbox" name="is_active" value="1" @checked($t->is_active)></td>
                            <td class="px-4 py-2 flex gap-2">
                                <button class="text-blue-600 hover:underline">Save</button>
                        </form>
                                @if(! $t->is_active)
                                    <form method="POST" action="{{ route('admin.terms.activate', $t) }}">
                                        @csrf
                                        <button class="text-emerald-600 hover:underline">Activate</button>
                                    </form>
                                @endif
                            </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No terms yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $terms->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Add Term</h2>
        <form method="POST" action="{{ route('admin.terms.store') }}" class="space-y-3">
            @csrf
            <input name="school_year" placeholder="e.g. 2026-2027" pattern="\d{4}-\d{4}" required class="w-full border rounded px-3 py-2">
            <select name="semester" required class="w-full border rounded px-3 py-2">
                <option value="">Semester…</option>
                <option>1st</option><option>2nd</option><option>Summer</option>
            </select>
            <input type="date" name="start_date" required class="w-full border rounded px-3 py-2">
            <input type="date" name="end_date" required class="w-full border rounded px-3 py-2">
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="is_active" value="1" class="mr-2"> Make active
            </label>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Create</button>
        </form>
    </div>
</div>
@endsection
