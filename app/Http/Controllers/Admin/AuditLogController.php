<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $q = AuditLog::with('user')->latest();

        if ($action = $request->input('action')) {
            $q->where('action', 'like', "%$action%");
        }
        if ($userId = $request->input('user_id')) {
            $q->where('user_id', $userId);
        }

        return view('admin.audit-logs', [
            'logs'   => $q->paginate(30)->withQueryString(),
            'filter' => ['action' => $action, 'user_id' => $userId],
        ]);
    }
}
