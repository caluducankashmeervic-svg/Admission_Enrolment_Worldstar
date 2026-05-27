@extends('layouts.app')
@section('title', 'Accreditation and Recognition')

@section('content')
<div class="max-w-5xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-5 md:p-8 mt-4 md:mt-6">
    <div class="border-l-4 border-amber-400 pl-3 md:pl-4 mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-[#1D4ED8] tracking-wide">Accreditation and Recognition</h1>
        <p class="text-sm text-slate-500 mt-1">Official accreditation and recognition photos posted by the administration.</p>
    </div>

    @if($items->isEmpty())
        <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">
            <p class="text-lg font-semibold text-slate-700">No content</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($items as $item)
                <article class="border border-slate-200 rounded-lg overflow-hidden bg-white">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title ?: 'Accreditation photo' }}" class="w-full h-52 object-cover">
                    <div class="px-4 py-3 space-y-0.5">
                        @if($item->title)
                            <p class="text-sm font-medium text-slate-700">{{ $item->title }}</p>
                        @endif
                        <p class="text-xs text-slate-400">{{ $item->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
