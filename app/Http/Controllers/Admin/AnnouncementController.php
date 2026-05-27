<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('admin.announcements', [
            'announcements' => Announcement::orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->paginate(15),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['published_at'] = $data['published_at'] ?? now();

        $announcement = Announcement::create($data);
        AuditLog::record('announcement.create', $announcement);

        return back()->with('status', 'Announcement created.');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validated($request);
        $data['published_at'] = $data['published_at'] ?? $announcement->published_at ?? now();

        $announcement->update($data);
        AuditLog::record('announcement.update', $announcement);

        return back()->with('status', 'Announcement updated.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}