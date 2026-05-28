@extends('layouts.app')
@section('title', 'Users')

@section('content')
<h1 class="text-2xl font-semibold">Users</h1>

<form method="GET" class="mt-4 flex flex-wrap gap-2 items-end">
    <input name="q" value="{{ $filter['q'] }}" placeholder="Search name/email"
           class="border rounded px-3 py-2">
    <select name="role" class="border rounded px-3 py-2">
        <option value="">All roles</option>
        @foreach(['admin','registrar','applicant'] as $r)
            <option value="{{ $r }}" @selected($filter['role']===$r)>{{ ucfirst($r) }}</option>
        @endforeach
    </select>
    <button class="bg-slate-700 text-white px-4 py-2 rounded">Filter</button>
    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-slate-600 hover:underline">Reset</a>
</form>

<div class="grid lg:grid-cols-3 gap-5 mt-5">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm table-fixed">
            <colgroup>
                <col class="w-[64px]">
                <col class="w-[30%]">
                <col class="w-[26%]">
                <col class="w-[16%]">
                <col class="w-[8%]">
                <col class="w-[20%]">
            </colgroup>
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-2">Photo</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Active</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr class="border-t border-slate-100">
                        <form method="POST" action="{{ route('admin.users.update', $u) }}">
                            @csrf @method('PUT')
                            <td class="px-4 py-2">
                                <img src="{{ $u->profile_photo_url }}"
                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=0D8ABC&color=fff&size=128'"
                                     class="w-10 h-10 rounded-full object-cover border border-slate-200" alt="">
                            </td>
                            <td class="px-4 py-2"><input name="name" value="{{ $u->name }}" class="w-full border rounded px-2 py-1"></td>
                            <td class="px-4 py-2 text-slate-500 truncate" title="{{ $u->email }}">{{ $u->email }}</td>
                            <td class="px-4 py-2">
                                <select name="role" class="border rounded px-2 py-1 w-full">
                                    @foreach(['admin','registrar','applicant'] as $r)
                                        <option value="{{ $r }}" @selected($u->role===$r)>{{ ucfirst($r) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-2"><input type="checkbox" name="is_active" value="1" @checked($u->is_active)></td>
                            <td class="px-4 py-2">
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
                                    <button class="text-blue-600 hover:underline">Save</button>
                                    <button type="button" onclick="togglePassword('{{ $u->id }}')" class="text-amber-700 hover:underline">Change Password</button>
                        </form>
                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                              onsubmit="return confirm('Delete user {{ addslashes($u->email) }}? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button class="text-rose-600 hover:underline text-sm">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                    </tr>
                    <tr id="pwd-{{ $u->id }}" class="hidden border-t border-blue-100 bg-blue-50/40">
                        <td colspan="6" class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.users.password', $u) }}" class="flex flex-wrap items-center gap-3">
                                @csrf @method('PUT')
                                <label class="text-sm text-slate-600">New password for <strong>{{ $u->email }}</strong></label>
                                <input type="password" name="password" minlength="8" required placeholder="Minimum 8 characters" class="border rounded px-3 py-1.5 w-64">
                                <button class="bg-amber-600 text-white text-sm px-3 py-1.5 rounded hover:bg-amber-700">Update Password</button>
                                <button type="button" onclick="togglePassword('{{ $u->id }}')" class="text-slate-600 hover:underline text-sm">Cancel</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No users.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $users->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
        <h2 class="font-semibold mb-3">Add User</h2>
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-3">
            @csrf
            <input name="name" placeholder="Full name" required class="w-full border rounded px-3 py-2">
            <input type="email" name="email" placeholder="Email" required class="w-full border rounded px-3 py-2">
            <input type="password" name="password" placeholder="Password (min 8)" minlength="8" required class="w-full border rounded px-3 py-2">
            <select name="role" required class="w-full border rounded px-3 py-2">
                <option value="">Role…</option>
                <option value="admin">Admin</option>
                <option value="registrar">Registrar</option>
                <option value="applicant">Applicant</option>
            </select>
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="is_active" value="1" checked class="mr-2"> Active
            </label>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Create</button>
        </form>
    </div>
</div>

<script>
function togglePassword(id) {
    const row = document.getElementById('pwd-' + id);
    if (row) {
        row.classList.toggle('hidden');
    }
}
</script>
@endsection
