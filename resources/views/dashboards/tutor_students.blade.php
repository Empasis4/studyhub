@extends('layouts.dashboard')
@section('title', 'Enrolled Students')
@section('content')
<div class="glass-card">
    <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-users" style="margin-right: 0.75rem; color: var(--primary);"></i>Enrolled Students Roster</h3>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">Overview of all students enrolled in your courses.</p>

    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="text-align:left; color:var(--text-muted); border-bottom:1px solid var(--glass-border);">
                <th style="padding:1rem;">Student Name</th>
                <th style="padding:1rem;">Course Enrolled</th>
                <th style="padding:1rem;">Join Date</th>
                <th style="padding:1rem;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($enrollments as $e)
            <tr style="border-bottom:1px solid var(--glass-border);">
                <td style="padding:1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width:32px; height:32px; border-radius:50%; background:rgba(99,102,241,0.1); color:var(--primary); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.8rem;">
                            {{ substr($e->student->Name ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight:600;">{{ $e->student->Name ?? 'Unknown' }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $e->student->Email ?? '' }}</div>
                        </div>
                    </div>
                </td>
                <td style="padding:1rem;">
                    <div style="font-weight:500; color:var(--text-main);">{{ $e->course->Title ?? 'N/A' }}</div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $e->course->category->CategoryName ?? '' }}</div>
                </td>
                <td style="padding:1rem; color:var(--text-muted); font-size:0.9rem;">
                    {{ $e->created_at ? $e->created_at->format('M d, Y') : '—' }}
                </td>
                <td style="padding:1rem;">
                    <span class="badge" style="background:rgba(16,185,129,0.1); color:#10b981;">Active</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:4rem; text-align:center; color:var(--text-muted);">
                    <i class="fas fa-user-slash" style="font-size:2rem; margin-bottom:1rem; display:block;"></i>
                    No students enrolled in your courses yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:1.5rem;">{{ $enrollments->links() }}</div>
</div>
@endsection
