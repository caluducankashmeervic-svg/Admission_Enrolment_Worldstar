@extends('layouts.app')
@section('title', 'Manage Enrollees')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Manage Enrollees</h1>
        <p class="text-sm text-slate-600">Review pending applications and confirm or deny admission.</p>
    </div>
    <form method="GET" class="flex items-center gap-2">
        @foreach (['pending' => 'Pending', 'confirmed' => 'Confirmed', 'denied' => 'Denied'] as $key => $label)
            <a href="{{ route('admin.enrollees.index', ['status' => $key]) }}"
               class="px-3 py-1.5 rounded-md text-sm border {{ $status === $key
                   ? 'bg-blue-600 text-white border-blue-600'
                   : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </form>
</div>

<div class="bg-white border border-slate-200 rounded-lg shadow-sm divide-y divide-slate-100">
    @forelse ($applicants as $a)
        <div class="flex items-center gap-4 px-5 py-4">
            <img src="{{ $a->user?->profile_photo_url
                ?? 'https://ui-avatars.com/api/?name='.urlencode($a->full_name).'&background=e2e8f0&color=475569' }}"
                 class="w-12 h-12 rounded-full object-cover border border-slate-200" alt="">
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-slate-900 truncate">{{ $a->full_name }}</div>
                <div class="text-xs text-slate-500 truncate">
                    {{ $a->reference_code }} ·
                    {{ optional($a->preferredCourse)->code ?? '—' }} ·
                    {{ optional($a->academicTerm)->school_year ?? '—' }}
                    <span class="inline-block ml-2 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] uppercase">
                        {{ str_replace('_', ' ', $a->status) }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if ($status !== 'confirmed')
                    <form method="POST" action="{{ route('admin.enrollees.confirm', $a) }}"
                          onsubmit="return confirm('Confirm this applicant?')">
                        @csrf
                        <button class="px-3 py-1.5 rounded-md bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                            Confirm
                        </button>
                    </form>
                @endif
                @if ($status !== 'denied')
                    <form method="POST" action="{{ route('admin.enrollees.deny', $a) }}"
                          onsubmit="return confirm('Deny this applicant?')">
                        @csrf
                        <button class="px-3 py-1.5 rounded-md bg-rose-600 text-white text-sm hover:bg-rose-700">
                            Deny
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.enrollees.show', $a) }}"
                   class="px-3 py-1.5 rounded-md bg-sky-600 text-white text-sm hover:bg-sky-700">
                    View
                </a>
            </div>
        </div>
    @empty
        <div class="px-5 py-10 text-center text-slate-500 text-sm">
            No applicants in this category.
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $applicants->links() }}</div>
@endsection
