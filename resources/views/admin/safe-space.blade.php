@extends('layouts.app')
@section('title', 'Safe Space Photos')

@section('content')
<div class="flex items-start justify-between">
    <h1 class="text-2xl font-semibold">Safe Space by Victory Church</h1>
</div>

<div class="grid lg:grid-cols-3 gap-5 mt-5">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($items as $item)
                <div class="p-4 space-y-3">
                    <form method="POST" action="{{ route('admin.safe-space.update', $item) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf @method('PUT')
                        <div class="grid md:grid-cols-[120px_1fr] gap-4">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="Safe Space photo" class="w-full h-24 object-cover rounded border border-slate-200">
                            <div class="space-y-2">
                                <input name="title" value="{{ $item->title }}" placeholder="Photo title" class="w-full border rounded px-3 py-2 text-sm">
                                <div class="grid md:grid-cols-2 gap-2">
                                    <input type="datetime-local" name="event_at" value="{{ optional($item->event_at)->format('Y-m-d\\TH:i') }}" class="w-full border rounded px-3 py-2 text-sm">
                                    <input type="file" name="photo" accept="image/*" class="w-full border rounded px-3 py-2 text-sm">
                                </div>
                                <label class="inline-flex items-center text-sm">
                                    <input type="checkbox" name="is_active" value="1" @checked($item->is_active) class="mr-2"> Visible to public
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-3 text-sm">
                            <button class="text-blue-600 hover:underline">Save</button>
                    </form>
                        <form method="POST" action="{{ route('admin.safe-space.destroy', $item) }}" onsubmit="return confirm('Delete this Safe Space photo?')">
                            @csrf @method('DELETE')
                            <button class="text-rose-600 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-slate-500">No Safe Space photos yet.</div>
            @endforelse
        </div>
        <div class="p-3">{{ $items->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Add New Photo</h2>
        <form method="POST" action="{{ route('admin.safe-space.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input name="title" placeholder="Photo title (optional)" class="w-full border rounded px-3 py-2">
            <input type="file" name="photo" accept="image/*" required class="w-full border rounded px-3 py-2">
            <input type="datetime-local" name="event_at" class="w-full border rounded px-3 py-2">
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="is_active" value="1" checked class="mr-2"> Visible to public
            </label>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Post Photo</button>
        </form>
    </div>
</div>
@endsection
