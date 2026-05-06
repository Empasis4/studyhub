@extends('layouts.dashboard')

@section('title', 'My Assessments')

@section('content')
<div style="margin-bottom: 2.5rem;">
    <h3 style="margin: 0;"><i class="fas fa-tasks" style="margin-right: 0.75rem; color: var(--primary);"></i>Assessment Tracker</h3>
    <p style="color: var(--text-muted); margin: 0.5rem 0 0 0;">Filter by course to view your pending tasks and check your graded scores.</p>
</div>

{{-- Course Selector --}}
<div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 1.5rem; margin-bottom: 1rem;">
    <a href="{{ route('student.assessments') }}" 
       class="glass-card {{ !$selectedCourse ? 'active-course' : '' }}" 
       style="min-width: 150px; padding: 1.25rem; text-decoration: none; text-align: center; transition: 0.3s; border: 1px solid {{ !$selectedCourse ? 'var(--primary)' : 'var(--glass-border)' }};">
        <div style="font-size: 1.2rem; margin-bottom: 0.5rem;"><i class="fas fa-globe"></i></div>
        <div style="font-weight: 600; font-size: 0.85rem;">All Tasks</div>
        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $pendingAssessments->count() }} Pending</div>
    </a>

    @foreach($courses as $course)
    <a href="{{ route('student.assessments', ['course_id' => $course->CourseID]) }}" 
       class="glass-card {{ $selectedCourse == $course->CourseID ? 'active-course' : '' }}" 
       style="min-width: 200px; padding: 1.25rem; text-decoration: none; transition: 0.3s; border: 1px solid {{ $selectedCourse == $course->CourseID ? 'var(--primary)' : 'var(--glass-border)' }};">
        <div style="font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $course->Title }}</div>
        <div style="font-size: 0.75rem; color: var(--text-muted);">
            {{ $pendingAssessments->where('lesson.module.CourseID', $course->CourseID)->count() }} Pending Tasks
        </div>
    </a>
    @endforeach
</div>

{{-- Pending Assessments --}}
@if($selectedCourse)
<div class="glass-card" style="margin-bottom: 2.5rem; border-top: 3px solid var(--primary);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-clock" style="color: #fbbf24; font-size: 1.2rem;"></i>
            <h4 style="margin: 0;">Pending Tasks</h4>
        </div>
        <span class="badge badge-purple">{{ $pendingAssessments->count() }} Remaining</span>
    </div>

    @forelse($pendingAssessments as $assessment)
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem; background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 12px; margin-bottom: 1rem; transition: 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.04)'" onmouseout="this.style.background='rgba(255,255,255,0.02)'">
        <div>
            <div style="font-weight: 600; margin-bottom: 0.25rem; color: white;">{{ $assessment->lesson->module->course->Title }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">
                <span style="color: var(--secondary);">Lesson:</span> {{ $assessment->lesson->Title ?? $assessment->lesson->ContentType }} | <span style="color: #fbbf24;">Due:</span> {{ \Carbon\Carbon::parse($assessment->DueDate)->format('M d, Y') }}
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            @if($assessment->AttachmentURL)
                <a href="{{ asset('storage/' . $assessment->AttachmentURL) }}" target="_blank" style="color: var(--secondary); text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem;" title="Download Task Document">
                    <i class="fas fa-paperclip"></i> Task File
                </a>
            @endif
            <a href="{{ route('student.lesson.view', $assessment->LessonID) }}" class="btn btn-primary" style="text-decoration: none; font-size: 0.85rem; padding: 0.5rem 1.25rem; border-radius: 8px;">
                Go to Lesson <i class="fas fa-arrow-right" style="margin-left: 0.5rem; font-size: 0.7rem;"></i>
            </a>
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 4rem; color: var(--text-muted); font-size: 0.9rem; background: rgba(255,255,255,0.01); border-radius: 12px;">
        <i class="fas fa-check-double" style="font-size: 3rem; margin-bottom: 1.5rem; display: block; opacity: 0.3; color: #10b981;"></i>
        All tasks completed for this course! Great job.
    </div>
    @endforelse
</div>
@else
<div class="glass-card" style="padding: 5rem; text-align: center; margin-bottom: 2.5rem;">
    <div style="font-size: 4rem; color: var(--primary); opacity: 0.2; margin-bottom: 1.5rem;">
        <i class="fas fa-mouse-pointer"></i>
    </div>
    <h4 style="margin: 0; color: var(--text-main);">Select a course above</h4>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">Pick a specific course to focus on your pending assessments.</p>
</div>
@endif

{{-- Completed Assessments / Scores --}}
<div class="glass-card">
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
        <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.2rem;"></i>
        <h4 style="margin: 0;">Completed & Graded ({{ $submissions->count() }})</h4>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
    @forelse($submissions as $sub)
    <div style="padding: 1.25rem; background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 12px; transition: 0.3s;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
            <div style="flex: 1;">
                <div style="font-weight: 600; margin-bottom: 0.25rem; color: white;">{{ $sub->assessment->lesson->module->course->Title }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">
                    Submitted: {{ $sub->created_at ? $sub->created_at->format('M d, Y') : 'N/A' }} 
                </div>
            </div>
            <div style="text-align: right;">
                @if($sub->Grade !== null)
                <div style="font-size: 1.1rem; font-weight: 700; color: #10b981;">{{ $sub->Grade }}/{{ $sub->assessment->TotalScore }}</div>
                @else
                <span class="badge" style="background: rgba(251,191,36,0.1); color: #fbbf24; font-size: 0.7rem;">Pending</span>
                @endif
            </div>
        </div>
        
        @if($sub->Feedback)
        <div style="background: rgba(99, 102, 241, 0.05); padding: 0.75rem; border-radius: 8px; border-left: 2px solid var(--primary); font-size: 0.85rem;">
            <p style="color: var(--text-main); margin: 0; font-style: italic;">"{{ $sub->Feedback }}"</p>
        </div>
        @endif
    </div>
    @empty
    <div style="grid-column: span 100; text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.9rem;">
        No completed assessments yet.
    </div>
    @endforelse
    </div>
</div>

<script>
    // Auto-refresh logic (kept from previous version)
    setTimeout(function() {
        location.reload();
    }, 120000); // Increased to 2 minutes
</script>

<div style="position: fixed; bottom: 2rem; right: 2rem; z-index: 100;">
    <button onclick="location.reload()" class="btn btn-primary" style="border-radius: 50px; padding: 1rem 1.5rem; box-shadow: 0 10px 25px rgba(99,102,241,0.4); animation: pulse 2s infinite;">
        <i class="fas fa-sync-alt" style="margin-right: 0.5rem;"></i>Sync Tasks
    </button>
</div>

<style>
    .active-course {
        background: rgba(99,102,241,0.1) !important;
        border-color: var(--primary) !important;
    }
    .badge-purple {
        background: rgba(168, 85, 247, 0.1);
        color: #a855f7;
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(99,102,241,0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(99,102,241,0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(99,102,241,0); }
    }
</style>
@endsection
