<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SafeSpacePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SafeSpaceController extends Controller
{
    public function index()
    {
        return view('admin.safe-space', [
            'items' => SafeSpacePhoto::orderByDesc('event_at')
                ->orderByDesc('created_at')
                ->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'event_at' => ['nullable', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $file = $request->file('photo');
        $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk('public')->putFileAs('safe-space', $file, $filename);

        $item = SafeSpacePhoto::create([
            'title' => $data['title'] ?? null,
            'image_path' => $path,
            'event_at' => $data['event_at'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record('safe_space.create', $item);

        return back()->with('status', 'Safe Space photo posted.');
    }

    public function update(Request $request, SafeSpacePhoto $safeSpace)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'event_at' => ['nullable', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $payload = [
            'title' => $data['title'] ?? null,
            'event_at' => $data['event_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('photo')) {
            $disk = Storage::disk('public');
            if ($safeSpace->image_path && $disk->exists($safeSpace->image_path)) {
                $disk->delete($safeSpace->image_path);
            }

            $file = $request->file('photo');
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $payload['image_path'] = $disk->putFileAs('safe-space', $file, $filename);
        }

        $safeSpace->update($payload);
        AuditLog::record('safe_space.update', $safeSpace);

        return back()->with('status', 'Safe Space photo updated.');
    }

    public function destroy(SafeSpacePhoto $safeSpace)
    {
        $disk = Storage::disk('public');
        if ($safeSpace->image_path && $disk->exists($safeSpace->image_path)) {
            $disk->delete($safeSpace->image_path);
        }

        AuditLog::record('safe_space.delete', $safeSpace);
        $safeSpace->delete();

        return back()->with('status', 'Safe Space photo deleted.');
    }
}
