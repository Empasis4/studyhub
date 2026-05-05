@extends('layouts.dashboard')

@section('title', 'Academic Administration')

@section('content')
{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 3rem;">
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-book" style="margin-right:0.4rem;"></i>My Courses</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;">{{ $stats['courses'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-users" style="margin-right:0.4rem;"></i>Total Students</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;">{{ $stats['students'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-layer-group" style="margin-right:0.4rem;"></i>Categories</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;">{{ $stats['categories'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-dollar-sign" style="margin-right:0.4rem;"></i>Revenue Value</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;color:#10b981;">${{ number_format($stats['revenue'],0) }}</div>
    </div>
</div>

@if(session('status'))
<div style="background:rgba(16,185,129,0.15);border:1px solid #10b981;border-radius:12px;padding:1rem 1.5rem;margin-bottom:2rem;color:#10b981;">
    <i class="fas fa-check-circle" style="margin-right:0.5rem;"></i>{{ session('status') }}
</div>
@endif

<div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;">
    {{-- Courses table --}}
    <div class="glass-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <h3>Your Courses</h3>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.8rem;text-decoration:none;">+ Create Course</a>
        </div>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="text-align:left;color:var(--text-muted);border-bottom:1px solid var(--glass-border);">
                    <th style="padding:0.75rem 1rem;">Course</th>
                    <th style="padding:0.75rem 1rem;">Category</th>
                    <th style="padding:0.75rem 1rem;">Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['recent_courses'] as $course)
                <tr style="border-bottom:1px solid var(--glass-border);">
                    <td style="padding:0.75rem 1rem;font-weight:600;">{{ $course->Title }}</td>
                    <td style="padding:0.75rem 1rem;"><span class="badge badge-purple">{{ $course->category->CategoryName ?? '—' }}</span></td>
                    <td style="padding:0.75rem 1rem;">${{ number_format($course->Price,2) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="padding:2rem;text-align:center;color:var(--text-muted);">No courses yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top:1rem;">
            <a href="{{ route('admin.courses') }}" style="font-size:0.85rem;color:var(--primary);text-decoration:none;">View all courses →</a>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="glass-card">
        <h3 style="margin-bottom:1.5rem;">Quick Actions</h3>
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <a href="{{ route('admin.categories.create') }}" class="btn glass-card" style="text-align:left;text-decoration:none;background:rgba(99,102,241,0.1);">
                <i class="fas fa-plus" style="margin-right:0.5rem;"></i> New Category
            </a>
            <a href="{{ route('admin.tutors.assign') }}" class="btn glass-card" style="text-align:left;text-decoration:none;">
                <i class="fas fa-user-plus" style="margin-right:0.5rem;"></i> Assign Tutor
            </a>
            <a href="{{ route('admin.reports.export') }}" class="btn glass-card" style="text-align:left;text-decoration:none;">
                <i class="fas fa-file-export" style="margin-right:0.5rem;"></i> Export Reports
            </a>
            <a href="{{ route('admin.analytics') }}" class="btn glass-card" style="text-align:left;text-decoration:none;">
                <i class="fas fa-chart-line" style="margin-right:0.5rem;"></i> View Analytics
            </a>
            <a href="{{ route('admin.settings') }}" class="btn glass-card" style="text-align:left;text-decoration:none;">
                <i class="fas fa-cog" style="margin-right:0.5rem;"></i> Site Settings
            </a>
        </div>
    </div>
</div>
@endsection
