<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return match (Auth::user()->role) {
                'admin'     => redirect()->route('admin.dashboard'),
                'registrar' => redirect()->route('registrar.applicants.index'),
                default     => redirect()->route('applicant.admission.create'),
            };
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginValue = trim($data['login']);
        $field      = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $field     => $loginValue,
            'password' => $data['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            AuditLog::record('auth.login');

            return match ($request->user()->role) {
                'admin'     => redirect()->route('admin.dashboard'),
                'registrar' => redirect()->route('registrar.applicants.index'),
                default     => redirect()->route('applicant.admission.create'),
            };
        }

        return back()->withErrors(['login' => 'Invalid credentials.'])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        AuditLog::record('auth.logout');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
