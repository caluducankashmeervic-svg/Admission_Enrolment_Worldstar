@extends('layouts.app')
@section('title', 'Courses')

@section('content')
<div class="flex items-start justify-between">
    <h1 class="text-2xl font-semibold">Courses</h1>
</div>

@error('course_delete')
    <div class="mt-4 rounded bg-rose-50 border border-rose-200 text-rose-800 px-3 py-2 text-sm">{{ $message }}</div>
@enderror

<div class="grid lg:grid-cols-3 gap-5 mt-5">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-2">Code</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Dept</th>
                    <th class="px-4 py-2">Quota</th>
                    <th class="px-4 py-2">Years</th>
                    <th class="px-4 py-2">Active</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $c)
                    <tr class="border-t border-slate-100">
                        <form method="POST" action="{{ route('admin.courses.update', $c) }}">
                            @csrf @method('PUT')
                            <td class="px-4 py-2 font-mono font-semibold">{{ $c->code }}</td>
                            <td class="px-4 py-2"><input name="name" value="{{ $c->name }}" class="w-full border rounded px-2 py-1"></td>
                            <td class="px-4 py-2"><input name="department" value="{{ $c->department }}" class="w-28 border rounded px-2 py-1"></td>
                            <td class="px-4 py-2"><input type="number" name="quota" value="{{ $c->quota }}" class="w-20 border rounded px-2 py-1"></td>
                            <td class="px-4 py-2"><input type="number" name="duration_years" value="{{ $c->duration_years }}" class="w-16 border rounded px-2 py-1"></td>
                            <td class="px-4 py-2"><input type="checkbox" name="is_active" value="1" @checked($c->is_active)></td>
                            <td class="px-4 py-2"><button class="text-blue-600 hover:underline">Save</button></td>
                        </form>
                        <td class="px-4 py-2">
                            <form method="POST" action="{{ route('admin.courses.destroy', $c) }}"
                                  onsubmit="return confirm('Delete course {{ addslashes($c->code) }}?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-slate-500">No courses yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $courses->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Add Course</h2>
        <form method="POST" action="{{ route('admin.courses.store') }}" class="space-y-3">
            @csrf
            <input name="code" placeholder="Code e.g. BSIT" required class="w-full border rounded px-3 py-2">
            <input name="name" placeholder="Course name" required class="w-full border rounded px-3 py-2">
            <input name="department" placeholder="Department" class="w-full border rounded px-3 py-2">
            <div class="grid grid-cols-2 gap-3">
                <input type="number" name="quota" value="60" min="0" class="w-full border rounded px-3 py-2" placeholder="Quota">
                <input type="number" name="duration_years" value="4" min="1" max="8" class="w-full border rounded px-3 py-2" placeholder="Years">
            </div>
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="is_active" value="1" checked class="mr-2"> Active
            </label>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Create</button>
        </form>
    </div>
</div>
@endsection
