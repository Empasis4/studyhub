@extends('layouts.dashboard')

@section('title', 'My Learning Journey')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3>Enrolled Courses</h3>
        <a href="{{ route('student.browse') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; text-decoration: none;">Browse More Courses</a>
    </div>
    
    <div class="course-grid" style="margin-top: 0;">
        @foreach($enrollments as $enrollment)
        <div class="glass-card course-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <span class="badge badge-purple">{{ $enrollment->course->category->CategoryName ?? 'General' }}</span>
                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $enrollment->ProgressPercentage }}% Complete</div>
            </div>
            <h2 style="margin: 0.5rem 0; font-size: 1.25rem;">{{ $enrollment->course->Title }}</h2>
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                <i class="fas fa-user-tie" style="margin-right: 0.4rem;"></i> Admin: {{ $enrollment->course->admin->Name }}
            </div>
            <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.05); border-radius: 10px; margin-bottom: 1.5rem;">
                <div style="width: {{ $enrollment->ProgressPercentage }}%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 10px;"></div>
            </div>
            <a href="{{ route('student.lesson.resume', $enrollment->course->CourseID) }}" class="btn btn-primary" style="width: 100%; text-align: center; display: block; text-decoration: none;">Resume Course</a>
        </div>
        @endforeach

        @if(count($enrollments) == 0)
        <div class="glass-card" style="grid-column: span 3; padding: 4rem; text-align: center;">
            <i class="fas fa-search" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1.5rem;"></i>
            <h2>Start your journey</h2>
            <p style="color: var(--text-muted); margin-top: 1rem;">You are not enrolled in any courses yet.</p>
        </div>
        @endif
    </div>
    
    <div style="margin-top: 2rem;">
        {{ $enrollments->links() }}
    </div>
</div>
@endsection
