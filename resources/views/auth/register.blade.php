@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 md:p-8">
        <h1 class="text-2xl font-bold text-slate-900">Create Your Student Account</h1>
        <p class="text-sm text-slate-600 mt-1">Create your account — fill in your details below.</p>

        <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data"
              class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div class="md:col-span-2 flex items-center gap-4">
                <img id="photo-preview"
                     src="https://ui-avatars.com/api/?name=?&background=e2e8f0&color=475569&size=96"
                     class="w-20 h-20 rounded-full object-cover border border-slate-200">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700">Profile Picture</label>
                    <input type="file" name="profile_photo" accept="image/*"
                           onchange="document.getElementById('photo-preview').src = window.URL.createObjectURL(this.files[0])"
                           class="mt-1 block w-full text-sm">
                    <p class="text-xs text-slate-500 mt-1">JPG / PNG / WEBP, max 2 MB.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Username <span class="text-rose-500">*</span></label>
                <input type="text" name="username" value="{{ old('username') }}" required
                       class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Password <span class="text-rose-500">*</span></label>
                <div class="mt-1 relative">
                    <input type="password" name="password" id="password" required minlength="8"
                           class="block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 pr-10 border">
                    <button type="button" onclick="togglePw('password', this)"
                            class="absolute inset-y-0 right-0 px-3 text-slate-400 hover:text-slate-600" aria-label="Show password">
                        <span class="eye">👁</span>
                    </button>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Confirm Password <span class="text-rose-500">*</span></label>
                <div class="mt-1 relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 pr-10 border">
                    <button type="button" onclick="togglePw('password_confirmation', this)"
                            class="absolute inset-y-0 right-0 px-3 text-slate-400 hover:text-slate-600" aria-label="Show password">
                        <span class="eye">👁</span>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Firstname <span class="text-rose-500">*</span></label>
                <input type="text" name="firstname" value="{{ old('firstname') }}" required
                       class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Lastname <span class="text-rose-500">*</span></label>
                <input type="text" name="lastname" value="{{ old('lastname') }}" required
                       class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Middlename</label>
                <input type="text" name="middlename" value="{{ old('middlename') }}"
                       class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Contact No. <span class="text-rose-500">*</span></label>
                <input type="text" name="contact_no" value="{{ old('contact_no') }}" required
                       class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 border">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Email (optional)</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 border">
            </div>

            <div class="md:col-span-2 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="reset"
                        class="px-5 py-2 rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">
                    Reset
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const isPw  = input.type === 'password';
        input.type  = isPw ? 'text' : 'password';
        btn.querySelector('.eye').textContent = isPw ? '🙈' : '👁';
    }
</script>
@endsection
