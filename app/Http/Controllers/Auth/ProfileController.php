<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('auth.profile', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password'         => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $payload = ['name' => $data['name']];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        if ($request->hasFile('profile_photo')) {
            $disk = Storage::disk(config('filesystems.profile_disk'));
            // Remove old photo
            if ($user->profile_photo_path && $disk->exists($user->profile_photo_path)) {
                $disk->delete($user->profile_photo_path);
            }
            $file     = $request->file('profile_photo');
            $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            $payload['profile_photo_path'] = $disk->putFileAs('profiles', $file, $filename);
        }

        $user->update($payload);
        AuditLog::record('profile.update', $user);

        return back()->with('status', 'Profile updated.');
    }
}
