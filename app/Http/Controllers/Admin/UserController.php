<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::query()->orderBy('name');
        if ($search = $request->input('q')) {
            $q->where(fn ($w) =>
                $w->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%"));
        }
        if ($role = $request->input('role')) {
            $q->where('role', $role);
        }

        return view('admin.users', [
            'users'  => $q->paginate(20)->withQueryString(),
            'filter' => ['q' => $search, 'role' => $role],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            'role'      => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_REGISTRAR, User::ROLE_APPLICANT])],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        $user = User::create($data);
        AuditLog::record('user.create', $user);
        return back()->with('status', "User {$user->email} created.");
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'role'      => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_REGISTRAR, User::ROLE_APPLICANT])],
            'is_active' => ['sometimes', 'boolean'],
            'password'  => ['nullable', 'string', 'min:8'],
        ]);

        $payload = [
            'name'      => $data['name'],
            'role'      => $data['role'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];
        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }
        $user->update($payload);
        AuditLog::record('user.update', $user);
        return back()->with('status', "User {$user->email} updated.");
    }
}
