@extends('layouts.app')
@section('title', 'Rejected Applicants')

@section('content')
<div class="flex items-start justify-between flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-semibold">Rejected Applicants</h1>
        <p class="text-sm text-slate-500 mt-1">These applicants have been rejected. You can permanently delete their data here.</p>
    </div>
</div>

@if(session('status'))
    <div class="mt-4 rounded border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-800">
        {{ session('status') }}
    </div>
@endif

<div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-x-auto mt-5">
    <table class="w-full text-sm">
        <thead class="bg-rose-50 text-left text-slate-600">
            <tr>
                <th class="px-3 py-2">Ref Code</th>
                <th class="px-3 py-2">Name</th>
                <th class="px-3 py-2">Course</th>
                <th class="px-3 py-2">Term</th>
                <th class="px-3 py-2">Mobile</th>
                <th class="px-3 py-2">Rejected On</th>
                <th class="px-3 py-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($applicants as $a)
                <tr class="border-t border-slate-100 hover:bg-rose-50/40">
                    <td class="px-3 py-2 font-mono text-xs">{{ $a->reference_code }}</td>
                    <td class="px-3 py-2">{{ $a->full_name }}</td>
                    <td class="px-3 py-2">{{ $a->preferredCourse?->code }}</td>
                    <td class="px-3 py-2 text-xs">{{ $a->academicTerm?->school_year }} {{ $a->academicTerm?->semester }}</td>
                    <td class="px-3 py-2 text-xs">{{ $a->mobile }}</td>
                    <td class="px-3 py-2 text-xs text-slate-500">{{ $a->updated_at->format('M d, Y') }}</td>
                    <td class="px-3 py-2">
                        <form method="POST" action="{{ route('admin.trash.destroy', $a) }}"
                              onsubmit="return confirm('Permanently delete {{ addslashes($a->full_name) }}? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button class="bg-rose-600 text-white text-xs px-3 py-1 rounded hover:bg-rose-700">
                                Delete Forever
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-3 py-10 text-center text-slate-500">
                        <p class="text-lg font-semibold">No rejected applicants</p>
                        <p class="text-sm mt-1">No rejected applicants found.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3">{{ $applicants->links() }}</div>
</div>
@endsection
