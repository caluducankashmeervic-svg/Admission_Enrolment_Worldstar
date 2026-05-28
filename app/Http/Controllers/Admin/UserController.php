<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Enrollment;
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
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'      => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_REGISTRAR, User::ROLE_APPLICANT])],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $payload = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];
        $user->update($payload);
        AuditLog::record('user.update', $user);
        return back()->with('status', "User {$user->email} updated.");
    }

    public function updatePassword(Request $request, User $user)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        AuditLog::record('user.password.update', $user);
        return back()->with('status', "Password updated for {$user->email}.");
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot delete your own account.');

        // Nullify processed_by FK on enrollments (no cascade defined on that column)
        Enrollment::where('processed_by', $user->id)->update(['processed_by' => null]);

        AuditLog::record('user.delete', $user);
        $email = $user->email;
        $user->delete();

        return back()->with('status', "User {$email} deleted.");
    }
}
