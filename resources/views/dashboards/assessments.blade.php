@extends('layouts.dashboard')
@section('title', 'Manage Assessments')
@section('content')

{{-- Header with Create Button --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h3 style="margin: 0;">Assigned Tasks</h3>
        <p style="color: var(--text-muted); margin: 0.5rem 0 0 0;">Manage your course assessments and grade student work.</p>
    </div>
    <button onclick="openModal('createModal')" class="btn btn-primary" style="padding: 0.8rem 1.5rem; border-radius: 12px;">
        <i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i>Create New Assessment
    </button>
</div>

@if(session('status'))
<div style="background: rgba(16,185,129,0.15); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #10b981;">
    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ session('status') }}
</div>
@endif

@if($errors->any())
<div style="background: rgba(239,68,68,0.15); border: 1px solid #ef4444; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #ef4444;">
    <ul style="margin:0; padding-left:1.5rem;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Assessment Table --}}
<div class="glass-card" style="padding: 0; overflow: hidden;">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="text-align:left; color:var(--text-muted); background: rgba(255,255,255,0.02); border-bottom:1px solid var(--glass-border);">
                <th style="padding:1.25rem;">Course & Module</th>
                <th style="padding:1.25rem;">Due Date</th>
                <th style="padding:1.25rem;">Max Score</th>
                <th style="padding:1.25rem;">Attachment</th>
                <th style="padding:1.25rem; text-align: center;">Submissions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assessments as $a)
            <tr style="border-bottom:1px solid var(--glass-border); transition: 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                <td style="padding:1.25rem;">
                    <div style="font-weight:600; color: white;">{{ $a->lesson->module->course->Title ?? '—' }}</div>
                    <div style="font-size:0.8rem; color:var(--text-muted);">{{ $a->lesson->module->ModuleTitle ?? '—' }}</div>
                </td>
                <td style="padding:1.25rem; color:var(--text-muted);">{{ \Carbon\Carbon::parse($a->DueDate)->format('M d, Y') }}</td>
                <td style="padding:1.25rem; font-weight: 600;">{{ $a->TotalScore }} pts</td>
                <td style="padding:1.25rem;">
                    @if($a->AttachmentURL)
                        <a href="{{ asset('storage/' . $a->AttachmentURL) }}" target="_blank" style="color: var(--secondary); text-decoration: none;" title="Download Task"><i class="fas fa-paperclip"></i> View File</a>
                    @else
                        <span style="color: var(--text-muted); font-size: 0.8rem;">No file</span>
                    @endif
                </td>
                <td style="padding:1.25rem; text-align: center;">
                    <button onclick="openSubmissionsModal('submissions_{{ $a->AssessmentID }}')" style="background:rgba(99,102,241,0.1); color:var(--primary); padding:0.4rem 1rem; border: 1px solid var(--primary); border-radius:20px; font-size:0.85rem; font-weight: 600; cursor: pointer; transition: 0.3s;">
                        {{ $a->submissions_count }} Submissions
                    </button>
                    
                    {{-- Submissions Modal for this Assessment --}}
                    <div id="submissions_{{ $a->AssessmentID }}" class="custom-modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8); backdrop-filter: blur(8px);">
                        <div class="glass-card" style="width: 800px; max-height: 80vh; margin: 5vh auto; overflow-y: auto; padding: 2rem; position: relative; border: 1px solid var(--primary);">
                            <span onclick="closeModal('submissions_{{ $a->AssessmentID }}')" style="position:absolute; top:1rem; right:1.5rem; font-size:1.5rem; cursor:pointer; color:var(--text-muted);">&times;</span>
                            
                            <h3 style="margin-bottom: 0.5rem;">Submissions for:</h3>
                            <h4 style="color: var(--primary); margin-bottom: 2rem;">{{ $a->lesson->module->ModuleTitle }}</h4>
                            
                            @forelse($a->submissions as $sub)
                                <div style="padding: 1.5rem; background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border); border-radius: 16px; margin-bottom: 1rem; text-align: left;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <div style="width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,var(--primary),var(--secondary)); display:flex; align-items:center; justify-content:center; font-weight:700;">
                                                {{ substr($sub->student->Name ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="font-weight:600;">{{ $sub->student->Name ?? 'Unknown' }}</div>
                                                <div style="font-size:0.75rem; color:var(--text-muted);">{{ $sub->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $sub->FileURL) }}" target="_blank" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; text-decoration: none;">
                                            <i class="fas fa-eye" style="margin-right: 0.3rem;"></i>View Work
                                        </a>
                                    </div>

                                    @if($sub->Grade === null)
                                        <form action="{{ route('tutor.feedback.grade', $sub->SubmissionID) }}" method="POST" style="display: flex; gap: 1rem; align-items: flex-end; background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 12px;">
                                            @csrf
                                            <div style="flex: 0 0 100px;">
                                                <label style="display:block; color:var(--text-muted); font-size:0.75rem; margin-bottom:0.3rem;">Score (Max: {{ $a->TotalScore }})</label>
                                                <input type="number" name="Grade" max="{{ $a->TotalScore }}" required style="width:100%; padding:0.6rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:8px; color:white;">
                                            </div>
                                            <div style="flex: 1;">
                                                <label style="display:block; color:var(--text-muted); font-size:0.75rem; margin-bottom:0.3rem;">Feedback</label>
                                                <input type="text" name="Feedback" placeholder="Write comments..." style="width:100%; padding:0.6rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:8px; color:white;">
                                            </div>
                                            <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.2rem;">Grade</button>
                                        </form>
                                    @else
                                        <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(16,185,129,0.05); padding: 1rem; border-radius: 12px; border: 1px solid rgba(16,185,129,0.2);">
                                            <div>
                                                <span style="color: #10b981; font-weight: 700; font-size: 1.1rem;">{{ $sub->Grade }} / {{ $a->TotalScore }}</span>
                                                <span style="font-size: 0.8rem; color: var(--text-muted); margin-left: 0.5rem;">Grade Recorded</span>
                                            </div>
                                            <div style="font-style: italic; font-size: 0.85rem; color: var(--text-muted);">"{{ $sub->Feedback }}"</div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                    <p>No submissions yet for this assessment.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="padding:4rem; text-align:center; color:var(--text-muted);">No assessments found. Start by creating one!</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Create Assessment Modal --}}
<div id="createModal" class="custom-modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8); backdrop-filter: blur(8px);">
    <div class="glass-card" style="width: 500px; margin: 10vh auto; padding: 2.5rem; position: relative; border: 1px solid var(--primary);">
        <span onclick="closeModal('createModal')" style="position:absolute; top:1rem; right:1.5rem; font-size:1.5rem; cursor:pointer; color:var(--text-muted);">&times;</span>
        
        <h3 style="margin-bottom: 2rem;"><i class="fas fa-plus-circle" style="margin-right: 0.75rem; color: var(--primary);"></i>New Assessment</h3>
        
        <form action="{{ route('tutor.assessments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Select Lesson</label>
                <select name="LessonID" required style="width:100%; padding:0.9rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:12px; color:white;">
                    <option value="">— Choose a lesson —</option>
                    @foreach($lessons ?? [] as $lesson)
                        <option value="{{ $lesson->LessonID }}">{{ $lesson->module->course->Title ?? '' }} → {{ $lesson->module->ModuleTitle ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Due Date</label>
                    <input type="date" name="DueDate" required style="width:100%; padding:0.9rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:12px; color:white;">
                </div>
                <div>
                    <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Total Score</label>
                    <input type="number" name="TotalScore" required placeholder="e.g. 100" style="width:100%; padding:0.9rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:12px; color:white;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Task Attachment (Optional)</label>
                <input type="file" name="AttachmentFile" style="width:100%; padding:0.8rem; background:rgba(255,255,255,0.02); border:1px dashed var(--glass-border); border-radius:12px; color:var(--text-muted); font-size:0.85rem;">
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Instructions</label>
                <textarea name="Instructions" rows="4" placeholder="Describe the task..." style="width:100%; padding:0.9rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:12px; color:white; resize:none;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; padding:1rem; font-weight: 600;">
                Create Assessment Task
            </button>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function openSubmissionsModal(id) {
        openModal(id);
    }

    // Close when clicking outside modal content
    window.onclick = function(event) {
        if (event.target.className === 'custom-modal') {
            event.target.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
</script>

<style>
    .custom-modal {
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .custom-modal > div {
        animation: slideUp 0.4s ease-out;
    }
    @keyframes slideUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

@endsection
