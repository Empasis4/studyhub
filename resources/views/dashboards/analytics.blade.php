@extends('layouts.dashboard')
@section('title', 'Platform Analytics')
@section('content')

{{-- Stats Row --}}
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 2rem;">
    <div class="card-stat">
        <div style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem;"><i class="fas fa-users" style="margin-right: 0.4rem;"></i>Total Users</div>
        <div style="font-size: 2.2rem; font-weight: 800;">{{ $data['total_users'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem;"><i class="fas fa-book" style="margin-right: 0.4rem;"></i>Total Courses</div>
        <div style="font-size: 2.2rem; font-weight: 800;">{{ $data['total_courses'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem;"><i class="fas fa-user-graduate" style="margin-right: 0.4rem;"></i>Enrollments</div>
        <div style="font-size: 2.2rem; font-weight: 800;">{{ $data['total_enrolled'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem;"><i class="fas fa-dollar-sign" style="margin-right: 0.4rem;"></i>Total Revenue</div>
        <div style="font-size: 2.2rem; font-weight: 800; color: #10b981;">${{ number_format($data['total_revenue'], 0) }}</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    {{-- User Role Breakdown --}}
    <div class="glass-card">
        <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-chart-pie" style="margin-right: 0.75rem; color: var(--primary);"></i>User Roles Breakdown</h3>
        @php
            $total = $data['total_users'] ?: 1;
            $roles = [
                ['name' => 'Admins',   'count' => $data['by_role']['admins'],   'color' => '#6366f1'],
                ['name' => 'Tutors',   'count' => $data['by_role']['tutors'],   'color' => '#a855f7'],
                ['name' => 'Students', 'count' => $data['by_role']['students'], 'color' => '#f472b6'],
            ];
        @endphp
        @foreach($roles as $role)
        <div style="margin-bottom: 1.2rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem;">
                <span style="font-size: 0.9rem;">{{ $role['name'] }}</span>
                <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $role['count'] }} ({{ round($role['count']/$total*100) }}%)</span>
            </div>
            <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 10px;">
                <div style="width: {{ round($role['count']/$total*100) }}%; height: 100%; background: {{ $role['color'] }}; border-radius: 10px; transition: width 1s ease;"></div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Category Breakdown --}}
    <div class="glass-card">
        <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-layer-group" style="margin-right: 0.75rem; color: var(--secondary);"></i>Courses by Category</h3>
        @php $totalCourses = $data['categories']->sum('courses_count') ?: 1; @endphp
        @forelse($data['categories'] as $cat)
        <div style="margin-bottom: 1.2rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem;">
                <span style="font-size: 0.9rem;">{{ $cat->CategoryName }}</span>
                <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $cat->courses_count }} courses</span>
            </div>
            <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 10px;">
                <div style="width: {{ round($cat->courses_count/$totalCourses*100) }}%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 10px;"></div>
            </div>
        </div>
        @empty
        <p style="color: var(--text-muted);">No categories found.</p>
        @endforelse
    </div>
</div>

{{-- Top Courses Table --}}
<div class="glass-card">
    <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-trophy" style="margin-right: 0.75rem; color: #fbbf24;"></i>Top Enrolled Courses</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--glass-border);">
                <th style="padding: 0.75rem 1rem;">Rank</th>
                <th style="padding: 0.75rem 1rem;">Course</th>
                <th style="padding: 0.75rem 1rem;">Enrollments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['top_courses'] as $i => $course)
            <tr style="border-bottom: 1px solid var(--glass-border);">
                <td style="padding: 0.75rem 1rem; font-weight: 700; color: {{ $i === 0 ? '#fbbf24' : 'var(--text-muted)' }};">#{{ $i+1 }}</td>
                <td style="padding: 0.75rem 1rem; font-weight: 600;">{{ $course->Title }}</td>
                <td style="padding: 0.75rem 1rem;">
                    <span style="background: rgba(99,102,241,0.15); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;">{{ $course->enrollments_count }} students</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
