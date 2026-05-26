<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function show(Request $request)
    {
        if ($request->user()) {
            return redirect()->route('home');
        }
        $pendingId = $request->session()->get('pending_applicant_id');
        if (! $pendingId) {
            return redirect()->route('applicant.code.form')
                ->withErrors(['reference_code' => 'Please verify your reference code first.']);
        }
        return view('auth.register', [
            'pendingCode' => $request->session()->get('pending_applicant_code'),
            'applicant'   => Applicant::find($pendingId),
        ]);
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

        // Reference-code binding: must have come from /apply/code with a valid unbound applicant.
        $pendingId = $request->session()->get('pending_applicant_id');
        $applicant = $pendingId ? Applicant::find($pendingId) : null;
        if (! $applicant) {
            return back()
                ->withInput()
                ->withErrors(['reference_code' => 'You must verify a reference code before creating an account.']);
        }
        if ($applicant->user_id) {
            $request->session()->forget(['pending_applicant_id', 'pending_applicant_code']);
            return redirect()->route('applicant.code.form')
                ->withErrors(['reference_code' => 'This reference code has already been bound to an account.']);
        }

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

        // Bind the applicant to the new user (one-way; reference code can no longer be reused).
        $applicant->user_id = $user->id;
        $applicant->save();

        $request->session()->forget(['pending_applicant_id', 'pending_applicant_code']);

        AuditLog::record('auth.register', $user);
        AuditLog::record('applicant.code_bound', $applicant);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('applicant.admission.create')
            ->with('status', 'Account created and reference code '.$applicant->reference_code.' bound. Continue your enrollment progress here.');
    }
}
