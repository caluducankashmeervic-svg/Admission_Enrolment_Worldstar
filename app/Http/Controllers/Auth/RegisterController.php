<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username'      => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'firstname'     => ['required', 'string', 'max:100'],
            'lastname'      => ['required', 'string', 'max:100'],
            'middlename'    => ['nullable', 'string', 'max:100'],
            'contact_no'    => ['required', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:150', 'unique:users,email'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $file      = $request->file('profile_photo');
            $filename  = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            $photoPath = Storage::disk(config('filesystems.profile_disk'))
                ->putFileAs('profiles', $file, $filename);
        }

        $user = User::create([
            'name'               => trim($data['firstname'].' '.$data['lastname']),
            'email'              => $data['email'] ?? $data['username'].'@student.local',
            'password'           => Hash::make($data['password']),
            'role'               => User::ROLE_APPLICANT,
            'is_active'          => true,
            'username'           => $data['username'],
            'firstname'          => $data['firstname'],
            'lastname'           => $data['lastname'],
            'middlename'         => $data['middlename'] ?? null,
            'contact_no'         => $data['contact_no'],
            'profile_photo_path' => $photoPath,
        ]);

        AuditLog::record('auth.register', $user);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('applicant.admission.create')
            ->with('status', 'Account created. Please complete your admission form.');
    }
}
