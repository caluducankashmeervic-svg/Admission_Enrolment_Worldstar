@extends('layouts.app')
@section('title', 'Announcements')

@section('content')
<div class="flex items-start justify-between">
    <h1 class="text-2xl font-semibold">Announcements</h1>
</div>

<div class="grid lg:grid-cols-3 gap-5 mt-5">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($announcements as $announcement)
                <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="p-5 space-y-3">
                    @csrf @method('PUT')
                    <div class="grid md:grid-cols-[1fr_auto] gap-3 items-start">
                        <input name="title" value="{{ $announcement->title }}" class="w-full border rounded px-3 py-2 font-semibold" required>
                        <input type="datetime-local" name="published_at" value="{{ optional($announcement->published_at)->format('Y-m-d\TH:i') }}" class="border rounded px-3 py-2">
                    </div>
                    <textarea name="body" rows="5" class="w-full border rounded px-3 py-2" required>{{ $announcement->body }}</textarea>
                    <div class="flex items-center justify-between gap-4">
                        <label class="inline-flex items-center text-sm">
                            <input type="checkbox" name="is_active" value="1" @checked($announcement->is_active) class="mr-2"> Active
                        </label>
                        <div class="flex items-center gap-3">
                            <button class="text-blue-600 hover:underline text-sm">Save</button>
                        </div>
                    </div>
                </form>
                <div class="px-5 pb-4 flex justify-end">
                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}"
                          onsubmit="return confirm('Delete this announcement permanently?')">
                        @csrf @method('DELETE')
                        <button class="text-rose-600 hover:underline text-sm">Delete</button>
                    </form>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-slate-500">No announcements yet.</div>
            @endforelse
        </div>
        <div class="p-3">{{ $announcements->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Add Announcement</h2>
        <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-3">
            @csrf
            <input name="title" placeholder="Announcement title" required class="w-full border rounded px-3 py-2">
            <textarea name="body" rows="6" placeholder="Write the announcement details here" required class="w-full border rounded px-3 py-2"></textarea>
            <input type="datetime-local" name="published_at" class="w-full border rounded px-3 py-2">
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="is_active" value="1" checked class="mr-2"> Active
            </label>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Create</button>
        </form>
    </div>
</div>
@endsection