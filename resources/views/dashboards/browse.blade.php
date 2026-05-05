@extends('layouts.dashboard')

@section('title', 'Explore Courses')

@section('content')
@if(session('status'))
<div style="background: rgba(16,185,129,0.15); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #10b981;">
    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ session('status') }}
</div>
@endif

<div class="glass-card" style="margin-bottom: 3rem; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1));">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem;">
        <div>
            <h2 style="margin-bottom: 0.5rem;">Find your next skill</h2>
            <p style="color: var(--text-muted);">Browse our industry-leading courses.</p>
        </div>
        <div style="display: flex; gap: 1rem; flex: 1; max-width: 400px; margin-left: 2rem;">
            <input type="text" placeholder="Search courses..." 
                   style="width: 100%; padding: 0.75rem 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: white;">
            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
        </div>
    </div>
</div>

<div class="course-grid">
    @forelse($courses as $course)
    <div class="glass-card course-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
            <span class="badge badge-purple">{{ $course->category->CategoryName ?? 'General' }}</span>
            <div style="font-weight: 700; color: var(--primary);">${{ number_format($course->Price, 2) }}</div>
        </div>
        <h2 style="margin: 0.5rem 0; font-size: 1.25rem;">{{ $course->Title }}</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
            {{ $course->Description ?? 'Unlock your potential with this comprehensive course guided by industry experts.' }}
        </p>
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 700;">
                {{ substr($course->admin->Name ?? '?', 0, 1) }}
            </div>
            <span style="font-size: 0.8rem; color: var(--text-muted);">Hosted by {{ $course->admin->Name ?? 'Admin' }}</span>
        </div>
        @if(in_array($course->CourseID, $enrolled ?? []))
            <button class="btn glass-card" style="width: 100%; text-align: center; color: #10b981; cursor: default;" disabled>
                <i class="fas fa-check-circle" style="margin-right: 0.4rem;"></i>Already Enrolled
            </button>
        @else
            <form action="{{ route('student.enroll', $course->CourseID) }}" method="POST" id="enroll-form-{{ $course->CourseID }}">
                @csrf
                <button type="submit" id="join-now-{{ $course->CourseID }}" class="btn btn-primary" style="width: 100%; text-align: center; transition: 0.2s;" 
                        onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    Enroll Now
                </button>
            </form>
        @endif
    </div>
    @empty
    <div class="glass-card" style="grid-column: span 3; padding: 4rem; text-align: center;">
        <i class="fas fa-book" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
        <h3>No courses available yet</h3>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">Check back soon!</p>
    </div>
    @endforelse
</div>

<div style="margin-top: 3rem;">
    {{ $courses->links() }}
</div>
@endsection
