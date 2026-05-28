@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="max-w-xl mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-6 mt-6">
    <h1 class="text-2xl font-semibold">My Profile</h1>
    <p class="text-sm text-slate-500 mt-1">{{ $user->email }} · <span class="capitalize">{{ $user->role }}</span></p>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
        @csrf

        {{-- Profile Photo --}}
        <div class="flex items-center gap-5">
            <img id="photo-preview" src="{{ $user->profile_photo_url }}"
                 onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff&size=128'"
                 class="w-24 h-24 rounded-full object-cover border-2 border-slate-200 shadow-sm" alt="Profile photo">
            <div>
                <label class="block text-sm font-medium text-slate-700">Profile Photo</label>
                <input type="file" name="profile_photo" accept="image/*"
                       onchange="document.getElementById('photo-preview').src = URL.createObjectURL(this.files[0])"
                       class="mt-1 block text-sm">
                <p class="text-xs text-slate-500 mt-1">JPG / PNG / WEBP, max 2 MB.</p>
            </div>
        </div>

        <hr>
        <label class="block">
            <span class="text-sm font-medium">Name</span>
            <input name="name" value="{{ old('name', $user->name) }}" required
                   class="mt-1 w-full border rounded px-3 py-2">
        </label>

        <hr>
        <p class="text-sm font-medium">Change Password (optional)</p>

        <label class="block">
            <span class="text-sm">Current Password</span>
            <input type="password" name="current_password" class="mt-1 w-full border rounded px-3 py-2">
        </label>
        <label class="block">
            <span class="text-sm">New Password</span>
            <input type="password" name="password" minlength="8" class="mt-1 w-full border rounded px-3 py-2">
        </label>
        <label class="block">
            <span class="text-sm">Confirm New Password</span>
            <input type="password" name="password_confirmation" minlength="8" class="mt-1 w-full border rounded px-3 py-2">
        </label>

        <button class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">Save</button>
    </form>
</div>
@endsection
