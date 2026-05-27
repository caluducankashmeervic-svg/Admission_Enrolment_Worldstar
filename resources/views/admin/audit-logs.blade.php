@extends('layouts.app')
@section('title', 'Audit Logs')

@section('content')
<h1 class="text-2xl font-semibold">Audit Logs</h1>

<form method="GET" class="mt-4 flex flex-wrap gap-2">
    <input name="action" value="{{ $filter['action'] }}" placeholder="Filter by action (e.g. enrollment.finalize)"
           class="border rounded px-3 py-2 w-80">
    <input name="user_id" value="{{ $filter['user_id'] }}" placeholder="User ID" type="number"
           class="border rounded px-3 py-2 w-28">
    <button class="bg-slate-700 text-white px-4 py-2 rounded">Filter</button>
    <a href="{{ route('admin.audit.index') }}" class="px-3 py-2 text-slate-600 hover:underline">Reset</a>
</form>

<div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-x-auto mt-5">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-600">
            <tr>
                <th class="px-4 py-2">When</th>
                <th class="px-4 py-2">User</th>
                <th class="px-4 py-2">Action</th>
                <th class="px-4 py-2">Entity</th>
                <th class="px-4 py-2">IP</th>
                <th class="px-4 py-2">Meta</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr class="border-t border-slate-100 align-top">
                    <td class="px-4 py-2 whitespace-nowrap">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                    <td class="px-4 py-2">{{ $log->user?->name ?? '—' }}</td>
                    <td class="px-4 py-2 font-mono text-xs">{{ $log->action }}</td>
                    <td class="px-4 py-2 text-xs">
                        @if($log->entity_type)
                            {{ class_basename($log->entity_type) }}#{{ $log->entity_id }}
                        @else — @endif
                    </td>
                    <td class="px-4 py-2 text-xs">{{ $log->ip_address }}</td>
                    <td class="px-4 py-2 text-xs text-slate-500">
                        @if($log->meta)
                            <code>{{ json_encode($log->meta, JSON_UNESCAPED_SLASHES) }}</code>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No logs.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3">{{ $logs->links() }}</div>
</div>
@endsection
