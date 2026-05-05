@extends('layouts.dashboard')

@section('title', 'My Assessments')

@section('content')
<div style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 0.5rem;">Assessment Tracker</h3>
    <p style="color: var(--text-muted);">View your pending tasks and check your graded scores.</p>
</div>

{{-- Pending Assessments --}}
<div class="glass-card" style="margin-bottom: 2.5rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
        <i class="fas fa-clock" style="color: #fbbf24; font-size: 1.2rem;"></i>
        <h4 style="margin: 0;">Pending Tasks ({{ $pendingAssessments->count() }})</h4>
    </div>

    @forelse($pendingAssessments as $assessment)
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem; background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 12px; margin-bottom: 1rem;">
        <div>
            <div style="font-weight: 600; margin-bottom: 0.25rem;">{{ $assessment->lesson->module->course->Title }}</div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">
                Lesson: {{ $assessment->lesson->ContentType }} | Due: {{ \Carbon\Carbon::parse($assessment->DueDate)->format('M d, Y') }}
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            @if($assessment->AttachmentURL)
                <a href="{{ asset('storage/' . $assessment->AttachmentURL) }}" target="_blank" style="color: var(--secondary); text-decoration: none; font-size: 0.85rem;" title="Download Task Document"><i class="fas fa-paperclip"></i> Task File</a>
            @endif
            <a href="{{ route('student.lesson.view', $assessment->LessonID) }}" class="btn btn-primary" style="text-decoration: none; font-size: 0.85rem; padding: 0.5rem 1rem;">Go to Lesson</a>
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.9rem;">
        No pending assessments. Great job!
    </div>
    @endforelse
</div>

{{-- Completed Assessments / Scores --}}
<div class="glass-card">
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
        <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.2rem;"></i>
        <h4 style="margin: 0;">Completed & Graded ({{ $submissions->count() }})</h4>
    </div>

    @forelse($submissions as $sub)
    <div style="padding: 1.5rem; background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 12px; margin-bottom: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
            <div>
                <div style="font-weight: 600; margin-bottom: 0.25rem;">{{ $sub->assessment->lesson->module->course->Title }}</div>
                <div style="font-size: 0.85rem; color: var(--text-muted);">
                    Submitted: {{ $sub->created_at ? $sub->created_at->format('M d, Y') : 'N/A' }} 
                    @if(Str::startsWith($sub->FileURL, 'submissions/'))
                        | <a href="{{ asset('storage/' . $sub->FileURL) }}" target="_blank" style="color: var(--primary); text-decoration: none;"><i class="fas fa-download"></i> View My Work</a>
                    @endif
                </div>
            </div>
            <div style="text-align: right;">
                @if($sub->Grade !== null)
                <div style="font-size: 1.2rem; font-weight: 700; color: #10b981;">{{ $sub->Grade }} / {{ $sub->assessment->TotalScore }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Score</div>
                @else
                <span class="badge" style="background: rgba(251,191,36,0.15); color: #fbbf24;">Pending Grade</span>
                @endif
            </div>
        </div>
        
        @if($sub->Feedback)
        <div style="background: rgba(99, 102, 241, 0.05); padding: 1rem; border-radius: 10px; border-left: 3px solid var(--primary);">
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--primary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Tutor Feedback</div>
            <p style="font-size: 0.9rem; color: var(--text-main); margin: 0; font-style: italic;">"{{ $sub->Feedback }}"</p>
        </div>
        @endif
    </div>
    @empty
    <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.9rem;">
        No completed assessments yet.
    </div>
    @endforelse
</div>
@endsection
