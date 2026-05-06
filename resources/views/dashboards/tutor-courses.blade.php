@extends('layouts.dashboard')

@section('title', 'My Teaching Courses')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3>Assigned Curriculum</h3>
        <a href="{{ route('tutor.modules') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; text-decoration: none;">+ Manage Modules</a>
    </div>
    
    <div class="course-grid" style="margin-top: 0;">
        @foreach($courses as $course)
        <div class="glass-card course-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <span class="badge badge-purple">{{ $course->category->CategoryName ?? 'General' }}</span>
                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $course->modules_count }} Modules</div>
            </div>
            <h2 style="margin: 0.5rem 0; font-size: 1.25rem;">{{ $course->Title }}</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $course->Description ?? 'No description available for this course.' }}
            </p>
            <a href="{{ route('tutor.lessons') }}" class="btn btn-primary" style="width: 100%; text-align: center; display: block; text-decoration: none;">Manage Lessons</a>
        </div>
        @endforeach

        @if(count($courses) == 0)
        <div class="glass-card" style="grid-column: span 3; padding: 4rem; text-align: center;">
            <i class="fas fa-chalkboard-teacher" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1.5rem;"></i>
            <h2>No courses assigned</h2>
            <p style="color: var(--text-muted); margin-top: 1rem;">You are not currently teaching any courses.</p>
        </div>
        @endif
    </div>
    
    <div style="margin-top: 2rem;">
        {{ $courses->links() }}
    </div>
</div>
@endsection
