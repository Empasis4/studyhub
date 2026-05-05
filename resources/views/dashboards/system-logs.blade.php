@extends('layouts.dashboard')
@section('title', 'System Logs')
@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3><i class="fas fa-history" style="margin-right: 0.75rem; color: var(--primary);"></i>System Audit Logs</h3>
        <span style="font-size: 0.85rem; color: var(--text-muted);">Last {{ $logs->count() }} entries</span>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--glass-border);">
                <th style="padding: 1rem;">ID</th>
                <th style="padding: 1rem;">Action</th>
                <th style="padding: 1rem;">IP Address</th>
                <th style="padding: 1rem;">Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr style="border-bottom: 1px solid var(--glass-border);">
                <td style="padding: 1rem; color: var(--text-muted);">#{{ $log->LogID }}</td>
                <td style="padding: 1rem;">
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-circle" style="font-size: 0.4rem; color: #10b981;"></i>
                        {{ $log->Action }}
                    </span>
                </td>
                <td style="padding: 1rem; font-family: monospace; color: var(--accent);">{{ $log->IPAddress }}</td>
                <td style="padding: 1rem; color: var(--text-muted); font-size: 0.85rem;">{{ $log->Timestamp }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 3rem; text-align: center; color: var(--text-muted);">No system logs recorded yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 2rem;">{{ $logs->links() }}</div>
</div>
@endsection
