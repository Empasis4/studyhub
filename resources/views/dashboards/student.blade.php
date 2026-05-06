@extends('layouts.dashboard')

@section('title', 'My Learning Path')

@section('content')
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 3rem;">
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-book-open" style="margin-right:0.4rem;"></i>Enrolled Courses</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;">{{ $stats['enrolled'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-check-circle" style="margin-right:0.4rem;"></i>Completed</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;color:#10b981;">{{ $stats['completed'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-chart-line" style="margin-right:0.4rem;"></i>Avg. Progress</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;color:var(--accent);">{{ $stats['avg_progress'] }}%</div>
    </div>
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-bell" style="margin-right:0.4rem;"></i>Notifications</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;color:#fbbf24;">{{ $stats['notifications'] }}</div>
    </div>
</div>

{{-- Quick Links --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-bottom:3rem;">
    <a href="{{ route('student.browse') }}" class="glass-card" style="text-decoration:none;text-align:center;padding:2rem;transition:0.3s;"
       onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--glass-border)'">
        <i class="fas fa-search" style="font-size:2rem;color:var(--primary);margin-bottom:1rem;"></i>
        <div style="font-weight:600;">Browse Courses</div>
    </a>
    <a href="{{ route('student.achievements') }}" class="glass-card" style="text-decoration:none;text-align:center;padding:2rem;transition:0.3s;"
       onmouseover="this.style.borderColor='#fbbf24'" onmouseout="this.style.borderColor='var(--glass-border)'">
        <i class="fas fa-trophy" style="font-size:2rem;color:#fbbf24;margin-bottom:1rem;"></i>
        <div style="font-weight:600;">Achievements</div>
    </a>
    <a href="{{ route('notifications') }}" class="glass-card" style="text-decoration:none;text-align:center;padding:2rem;transition:0.3s;"
       onmouseover="this.style.borderColor='var(--secondary)'" onmouseout="this.style.borderColor='var(--glass-border)'">
        <i class="fas fa-bell" style="font-size:2rem;color:var(--secondary);margin-bottom:1rem;"></i>
        <div style="font-weight:600;">Notifications</div>
        @if($stats['notifications'] > 0)
        <span style="background:var(--primary);color:white;padding:0.2rem 0.6rem;border-radius:12px;font-size:0.75rem;">{{ $stats['notifications'] }} new</span>
        @endif
    </a>
</div>

<h3 style="margin-bottom:1.5rem;">Continue Learning</h3>
<div class="course-grid">
    @foreach($enrollments as $enrollment)
    <div class="glass-card course-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
            <span class="badge badge-purple">{{ $enrollment->course->category->CategoryName ?? 'General' }}</span>
            <div style="font-size:0.8rem;color:var(--text-muted);">{{ $enrollment->ProgressPercentage }}%</div>
        </div>
        <h2 style="margin:0.5rem 0;font-size:1.2rem;">{{ $enrollment->course->Title }}</h2>
        <div style="width:100%;height:6px;background:rgba(255,255,255,0.05);border-radius:10px;margin:1.25rem 0;">
            <div style="width:{{ $enrollment->ProgressPercentage }}%;height:100%;background:linear-gradient(90deg,var(--primary),var(--secondary));border-radius:10px;"></div>
        </div>
        @php
            $firstModule = $enrollment->course->modules->first();
            $firstLesson = $firstModule ? $firstModule->lessons->first() : null;
        @endphp
        @if($firstLesson)
            <a href="{{ route('student.lesson.view', $firstLesson->LessonID) }}" class="btn btn-primary" style="width:100%;text-align:center;display:block;text-decoration:none;">Resume Lesson</a>
        @else
            <button class="btn glass-card" style="width:100%; cursor: not-allowed;" disabled>No Lessons Yet</button>
        @endif
    </div>
    @endforeach

    @if(count($enrollments) == 0)
    <div class="glass-card" style="grid-column:span 3;padding:4rem;text-align:center;">
        <i class="fas fa-search" style="font-size:3rem;color:var(--text-muted);margin-bottom:1.5rem;"></i>
        <h2>No enrollments yet</h2>
        <p style="color:var(--text-muted);margin:1rem 0 2rem;">Explore our catalog to start learning.</p>
        <a href="{{ route('student.browse') }}" class="btn btn-primary" style="text-decoration:none;">Browse All Courses</a>
    </div>
    @endif
</div>
@endsection
