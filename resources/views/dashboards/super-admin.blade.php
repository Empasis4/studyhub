@extends('layouts.dashboard')

@section('title', 'System Administration')

@section('content')
{{-- Stats Grid --}}
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 3rem;">
    <div class="card-stat">
        <div style="color: var(--text-muted); font-size: 0.9rem;"><i class="fas fa-users" style="margin-right:0.4rem;"></i>Total Users</div>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;">{{ $stats['users'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color: var(--text-muted); font-size: 0.9rem;"><i class="fas fa-user-tie" style="margin-right:0.4rem;"></i>Course Admins</div>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;">{{ $stats['admins'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color: var(--text-muted); font-size: 0.9rem;"><i class="fas fa-chalkboard-teacher" style="margin-right:0.4rem;"></i>Tutors</div>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;">{{ $stats['tutors'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color: var(--text-muted); font-size: 0.9rem;"><i class="fas fa-graduation-cap" style="margin-right:0.4rem;"></i>Students</div>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem; color: #10b981;">{{ $stats['students'] }}</div>
    </div>
</div>

{{-- Quick Actions --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <a href="{{ route('super.admin.users') }}" class="glass-card" style="text-decoration:none; display:flex; align-items:center; gap:1.5rem; padding:1.5rem; transition:0.3s;"
       onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--glass-border)'">
        <div style="width:50px;height:50px;border-radius:14px;background:rgba(99,102,241,0.15);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--primary);">
            <i class="fas fa-users-cog"></i>
        </div>
        <div>
            <div style="font-weight:700;">Manage Users</div>
            <div style="font-size:0.85rem;color:var(--text-muted);">View and edit all accounts</div>
        </div>
    </a>
    <a href="{{ route('super.admin.logs') }}" class="glass-card" style="text-decoration:none; display:flex; align-items:center; gap:1.5rem; padding:1.5rem; transition:0.3s;"
       onmouseover="this.style.borderColor='var(--secondary)'" onmouseout="this.style.borderColor='var(--glass-border)'">
        <div style="width:50px;height:50px;border-radius:14px;background:rgba(168,85,247,0.15);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--secondary);">
            <i class="fas fa-history"></i>
        </div>
        <div>
            <div style="font-weight:700;">System Logs</div>
            <div style="font-size:0.85rem;color:var(--text-muted);">View full audit trail</div>
        </div>
    </a>
</div>

{{-- Recent Logs --}}
<div class="glass-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h3><i class="fas fa-history" style="margin-right:0.75rem;color:var(--primary);"></i>Recent System Logs</h3>
        <a href="{{ route('super.admin.logs') }}" style="font-size:0.85rem;color:var(--primary);text-decoration:none;">View All →</a>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="text-align:left;color:var(--text-muted);border-bottom:1px solid var(--glass-border);">
                <th style="padding:0.75rem 1rem;">ID</th>
                <th style="padding:0.75rem 1rem;">Action</th>
                <th style="padding:0.75rem 1rem;">IP Address</th>
                <th style="padding:0.75rem 1rem;">Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stats['logs'] as $log)
            <tr style="border-bottom:1px solid var(--glass-border);">
                <td style="padding:0.75rem 1rem;color:var(--text-muted);">#{{ $log->LogID }}</td>
                <td style="padding:0.75rem 1rem;">{{ $log->Action }}</td>
                <td style="padding:0.75rem 1rem;font-family:monospace;color:var(--accent);">{{ $log->IPAddress }}</td>
                <td style="padding:0.75rem 1rem;color:var(--text-muted);font-size:0.85rem;">{{ $log->Timestamp }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:2rem;text-align:center;color:var(--text-muted);">No system logs recorded yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
