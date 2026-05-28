@extends('layouts.app')
@section('title', 'Accreditation Photos')

@section('content')
<div class="flex items-start justify-between">
    <h1 class="text-2xl font-semibold">Accreditation and Recognition</h1>
</div>

<div class="grid lg:grid-cols-3 gap-5 mt-5">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        @if($items->isEmpty())
            <div class="px-4 py-8 text-center text-slate-500">No content</div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($items as $item)
                    <article class="border border-slate-200 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $item->image_path) }}"
                             onerror="this.onerror=null;this.src='{{ asset('images/image.png') }}';this.classList.add('object-contain','bg-slate-50','p-4');"
                             alt="{{ $item->title ?: 'Accreditation photo' }}" class="w-full h-44 object-cover">
                        <div class="p-3 flex items-center justify-between gap-3">
                            <p class="text-sm text-slate-700 truncate">{{ $item->title ?: 'Untitled photo' }}</p>
                            <form method="POST" action="{{ route('admin.accreditations.destroy', $item) }}" onsubmit="return confirm('Delete this photo?');">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline text-sm">Delete</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-4">{{ $items->links() }}</div>
        @endif
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Post New Photo</h2>
        <form method="POST" action="{{ route('admin.accreditations.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input name="title" placeholder="Optional title" class="w-full border rounded px-3 py-2">
            <input type="file" name="photo" accept="image/*" required class="w-full border rounded px-3 py-2">
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="is_active" value="1" checked class="mr-2"> Visible to public
            </label>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Post Photo</button>
        </form>
    </div>
</div>
@endsection
