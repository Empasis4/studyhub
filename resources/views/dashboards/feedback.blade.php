@extends('layouts.dashboard')
@section('title', 'Student Feedback & Submissions')
@section('content')
@if(session('status'))
<div style="background: rgba(16,185,129,0.15); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #10b981;">
    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ session('status') }}
</div>
@endif

<div class="glass-card">
    <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-comment-dots" style="margin-right: 0.75rem; color: var(--primary);"></i>Student Submissions</h3>
    @forelse($submissions as $sub)
    <div style="padding: 1.5rem; border: 1px solid var(--glass-border); border-radius: 16px; margin-bottom: 1rem; background: rgba(255,255,255,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,var(--primary),var(--secondary)); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.9rem;">
                    {{ substr($sub->student->Name ?? '?', 0, 1) }}
                </div>
                <div>
                    <div style="font-weight:600;">{{ $sub->student->Name ?? 'Unknown' }}</div>
                    <div style="font-size:0.8rem; color:var(--text-muted);">{{ $sub->assessment->lesson->module->course->Title ?? 'N/A' }}</div>
                </div>
            </div>
            <div style="text-align:right;">
                @if($sub->Grade !== null)
                    <div style="font-size: 1.1rem; font-weight: 700; color: #10b981;">{{ $sub->Grade }}/{{ $sub->assessment->TotalScore ?? '—' }}</div>
                @else
                    <span class="badge" style="background: rgba(251,191,36,0.15); color: #fbbf24;">Pending</span>
                @endif
                <div style="font-size:0.75rem; color:var(--text-muted);">{{ $sub->created_at ? $sub->created_at->diffForHumans() : '' }}</div>
            </div>
        </div>

        {{-- Submission Content --}}
        <div style="background: rgba(255,255,255,0.03); border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.9rem; color: var(--text-muted);">
            @if(Str::startsWith($sub->FileURL, 'submissions/'))
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <i class="fas fa-file-alt" style="margin-right: 0.5rem; color: var(--secondary);"></i>
                        <span>Attached Document</span>
                    </div>
                    <a href="{{ asset('storage/' . $sub->FileURL) }}" target="_blank" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none;">
                        <i class="fas fa-eye" style="margin-right: 0.3rem;"></i>View Work
                    </a>
                </div>
            @elseif(filter_var($sub->FileURL, FILTER_VALIDATE_URL))
                <i class="fas fa-external-link-alt" style="margin-right: 0.5rem; color: var(--primary);"></i>
                <a href="{{ $sub->FileURL }}" target="_blank" style="color: var(--primary); text-decoration: none;">View Submitted Resource</a>
            @else
                <i class="fas fa-align-left" style="margin-right: 0.5rem; color: var(--primary);"></i>
                {{ $sub->FileURL ?? 'No answer submitted' }}
            @endif
        </div>

        @if($sub->Feedback)
        <div style="background: rgba(99,102,241,0.05); border-left: 3px solid var(--primary); padding: 0.75rem 1rem; border-radius: 0 8px 8px 0; font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">
            <strong>Your Feedback:</strong> {{ $sub->Feedback }}
        </div>
        @endif

        {{-- Grading Form --}}
        @if($sub->Grade === null)
        <form action="{{ route('tutor.feedback.grade', $sub->SubmissionID) }}" method="POST" style="display: flex; gap: 0.75rem; align-items: flex-end;">
            @csrf
            <div style="flex: 0 0 120px;">
                <label style="display:block; color:var(--text-muted); font-size:0.75rem; margin-bottom:0.3rem;">Grade (max: {{ $sub->assessment->TotalScore ?? 100 }})</label>
                <input type="number" name="Grade" min="0" max="{{ $sub->assessment->TotalScore ?? 100 }}" required placeholder="Score" style="width:100%; padding:0.6rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:10px; color:var(--text-main);">
            </div>
            <div style="flex: 1;">
                <label style="display:block; color:var(--text-muted); font-size:0.75rem; margin-bottom:0.3rem;">Feedback (optional)</label>
                <input type="text" name="Feedback" placeholder="Write feedback..." style="width:100%; padding:0.6rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:10px; color:var(--text-main);">
            </div>
            <button type="submit" class="btn btn-primary" style="padding:0.6rem 1.2rem; white-space:nowrap;">
                <i class="fas fa-check" style="margin-right:0.3rem;"></i>Grade
            </button>
        </form>
        @endif
    </div>
    @empty
    <div style="text-align: center; padding: 4rem; color: var(--text-muted);">
        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem;"></i>
        <div>No student submissions yet.</div>
    </div>
    @endforelse
    <div style="margin-top: 1.5rem;">{{ $submissions->links() }}</div>
</div>
@endsection
