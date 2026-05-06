@extends('layouts.dashboard')

@section('title', 'Instructor Dashboard')

@section('content')
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 3rem;">
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-layer-group" style="margin-right:0.4rem;"></i>Total Modules</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;">{{ $stats['modules'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-book" style="margin-right:0.4rem;"></i>Courses Teaching</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;">{{ $stats['courses_teaching'] }}</div>
    </div>
    <div class="card-stat">
        <div style="color:var(--text-muted);font-size:0.9rem;"><i class="fas fa-file-invoice" style="margin-right:0.4rem;"></i>Assessments</div>
        <div style="font-size:2rem;font-weight:700;margin-top:0.5rem;color:#fbbf24;">{{ $stats['assessments'] }}</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
    <div class="glass-card">
        <h3 style="margin-bottom:1.5rem;">Content Management</h3>
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <a href="{{ route('tutor.courses') }}" class="glass-card" style="display:flex;align-items:center;gap:1rem;padding:1.25rem;text-decoration:none;background:rgba(99,102,241,0.08);transition:0.3s;"
               onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                <i class="fas fa-chalkboard-teacher" style="color:var(--primary);font-size:1.3rem;"></i>
                <div><div style="font-weight:600;">My Courses</div><div style="font-size:0.8rem;color:var(--text-muted);">Manage curriculum</div></div>
            </a>
            <a href="{{ route('tutor.modules') }}" class="glass-card" style="display:flex;align-items:center;gap:1rem;padding:1.25rem;text-decoration:none;transition:0.3s;"
               onmouseover="this.style.borderColor='var(--secondary)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                <i class="fas fa-layer-group" style="color:var(--secondary);font-size:1.3rem;"></i>
                <div><div style="font-weight:600;">Modules</div><div style="font-size:0.8rem;color:var(--text-muted);">Manage course modules</div></div>
            </a>
            <a href="{{ route('tutor.lessons') }}" class="glass-card" style="display:flex;align-items:center;gap:1rem;padding:1.25rem;text-decoration:none;transition:0.3s;"
               onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                <i class="fas fa-play-circle" style="color:var(--accent);font-size:1.3rem;"></i>
                <div><div style="font-weight:600;">Lessons</div><div style="font-size:0.8rem;color:var(--text-muted);">Manage educational content</div></div>
            </a>
        </div>
    </div>

    <div class="glass-card">
        <h3 style="margin-bottom:1.5rem;">Assessment & Feedback</h3>
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <a href="{{ route('tutor.assessments') }}" class="glass-card" style="display:flex;align-items:center;gap:1rem;padding:1.25rem;text-decoration:none;transition:0.3s;"
               onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                <i class="fas fa-file-invoice" style="color:var(--primary);font-size:1.3rem;"></i>
                <div><div style="font-weight:600;">Assessments</div><div style="font-size:0.8rem;color:var(--text-muted);">Create & manage quizzes</div></div>
            </a>
            <a href="{{ route('tutor.feedback') }}" class="glass-card" style="display:flex;align-items:center;gap:1rem;padding:1.25rem;text-decoration:none;transition:0.3s;"
               onmouseover="this.style.borderColor='var(--secondary)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                <i class="fas fa-comment-dots" style="color:var(--secondary);font-size:1.3rem;"></i>
                <div><div style="font-weight:600;">Student Feedback</div><div style="font-size:0.8rem;color:var(--text-muted);">Review submissions</div></div>
            </a>
        </div>
    </div>
</div>
@endsection
