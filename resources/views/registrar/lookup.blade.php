@extends('layouts.app')
@section('title', 'Registrar Lookup')

@section('content')
<div class="max-w-xl mx-auto mt-8 bg-white border border-slate-200 rounded-lg shadow-sm p-6">
    <h1 class="text-2xl font-semibold">Applicant Lookup</h1>
    <p class="text-sm text-slate-500 mt-1">Enter the applicant's reference code to begin verification.</p>

    <form method="POST" action="{{ route('registrar.lookup.find') }}" class="mt-5 flex gap-3">
        @csrf
        <input name="reference_code" value="{{ old('reference_code') }}" placeholder="APP-XXXXXX"
               required autofocus class="flex-1 border rounded px-3 py-2 font-mono uppercase">
        <button class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">Find</button>
    </form>
</div>
@endsection
