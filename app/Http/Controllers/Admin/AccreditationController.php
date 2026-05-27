<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accreditation;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AccreditationController extends Controller
{
    public function index()
    {
        return view('admin.accreditations', [
            'items' => Accreditation::orderByDesc('created_at')->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $file = $request->file('photo');
        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $path = Storage::disk('public')->putFileAs('accreditations', $file, $filename);

        $item = Accreditation::create([
            'title' => $data['title'] ?? null,
            'image_path' => $path,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record('accreditation.create', $item);

        return back()->with('status', 'Accreditation photo posted.');
    }

    public function destroy(Accreditation $accreditation)
    {
        $disk = Storage::disk('public');
        if ($accreditation->image_path && $disk->exists($accreditation->image_path)) {
            $disk->delete($accreditation->image_path);
        }

        AuditLog::record('accreditation.delete', $accreditation);
        $accreditation->delete();

        return back()->with('status', 'Accreditation photo deleted.');
    }
}
