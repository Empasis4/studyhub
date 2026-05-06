@extends('layouts.dashboard')
@section('title', 'Manage Lessons')
@section('content')

{{-- Header with Create Button --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h3 style="margin: 0;">Course Lessons</h3>
        <p style="color: var(--text-muted); margin: 0.5rem 0 0 0;">Upload and manage educational content for your modules.</p>
    </div>
    <button onclick="openModal('createLessonModal')" class="btn btn-primary" style="padding: 0.8rem 1.5rem; border-radius: 12px;">
        <i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i>Add New Lesson
    </button>
</div>

@if(session('status'))
<div style="background: rgba(16,185,129,0.15); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #10b981;">
    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ session('status') }}
</div>
@endif

{{-- Lessons Table --}}
<div class="glass-card" style="padding: 0; overflow: hidden;">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="text-align:left; color:var(--text-muted); background: rgba(255,255,255,0.02); border-bottom:1px solid var(--glass-border);">
                <th style="padding:1.25rem;">Lesson Title</th>
                <th style="padding:1.25rem;">Module / Course</th>
                <th style="padding:1.25rem;">Type</th>
                <th style="padding:1.25rem; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lessons as $lesson)
            <tr style="border-bottom:1px solid var(--glass-border); transition: 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                <td style="padding:1.25rem;">
                    <div style="font-weight:600; color: white;">{{ $lesson->Title }}</div>
                </td>
                <td style="padding:1.25rem;">
                    <div style="font-size:0.9rem;">{{ $lesson->module->ModuleTitle ?? 'No Module' }}</div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $lesson->module->course->Title ?? 'Unknown Course' }}</div>
                </td>
                <td style="padding:1.25rem;">
                    <span class="badge badge-purple">{{ $lesson->ContentType }}</span>
                </td>
                <td style="padding:1.25rem; text-align: right;">
                    <a href="{{ asset('storage/' . $lesson->URL) }}" target="_blank" class="btn glass-card" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; text-decoration: none;">
                        <i class="fas fa-eye" style="margin-right: 0.3rem;"></i>View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:4rem; text-align:center; color:var(--text-muted);">
                    <i class="fas fa-play-circle" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.5;"></i>
                    No lessons found. Start by adding one!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem;">
    {{ $lessons->links() }}
</div>

{{-- Create Lesson Modal --}}
<div id="createLessonModal" class="custom-modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8); backdrop-filter: blur(8px);">
    <div class="glass-card" style="width: 600px; margin: 8vh auto; padding: 2.5rem; position: relative; border: 1px solid var(--primary); max-height: 85vh; overflow-y: auto;">
        <span onclick="closeModal('createLessonModal')" style="position:absolute; top:1rem; right:1.5rem; font-size:1.5rem; cursor:pointer; color:var(--text-muted);">&times;</span>
        
        <h3 style="margin-bottom: 2rem;"><i class="fas fa-plus-circle" style="margin-right: 0.75rem; color: var(--primary);"></i>New Lesson</h3>
        
        <form action="{{ route('tutor.lessons.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Lesson Title</label>
                <input type="text" name="Title" required placeholder="e.g. Lesson 1: Introduction" style="width:100%; padding:0.9rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:12px; color:white; outline: none;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Select Module</label>
                <select name="ModuleID" required style="width:100%; padding:0.9rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:12px; color:white; outline: none; appearance: none;">
                    <option value="" disabled selected>— Choose a module —</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod->ModuleID }}">{{ $mod->course->Title ?? 'Unknown' }} › {{ $mod->ModuleTitle }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Content Type</label>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem;">
                    @foreach(['Video','PDF','Quiz'] as $type)
                    <label style="cursor: pointer;">
                        <input type="radio" name="ContentType" value="{{ $type }}" {{ $type === 'Video' ? 'checked' : '' }} style="display:none;" class="content-radio">
                        <div class="content-type-card" data-type="{{ $type }}" style="padding: 0.75rem; border: 1px solid var(--glass-border); border-radius: 12px; text-align: center; transition: 0.3s; background: {{ $type === 'Video' ? 'rgba(99,102,241,0.15)' : 'rgba(255,255,255,0.02)' }};">
                            <i class="fas fa-{{ $type === 'Video' ? 'video' : ($type === 'PDF' ? 'file-pdf' : 'question-circle') }}" style="font-size: 1.25rem; margin-bottom: 0.3rem; color: {{ $type === 'Video' ? 'var(--primary)' : 'var(--text-muted)' }};"></i>
                            <div style="font-size: 0.8rem; font-weight: 600;">{{ $type }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Upload Content File</label>
                <input type="file" name="LessonFile" required id="lesson_file" style="width:100%; padding:0.8rem; background:rgba(255,255,255,0.02); border:1px dashed var(--glass-border); border-radius:12px; color:var(--text-muted); font-size:0.85rem;">
                <p id="file-hint" style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Supported: .mp4, .mov, .pdf (Max 100MB)</p>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 1rem; font-weight: 600;">Save Lesson</button>
                <button type="button" onclick="closeModal('createLessonModal')" class="btn glass-card" style="padding: 1rem 1.5rem;">Cancel</button>
            </div>
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

    document.querySelectorAll('.content-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('.content-type-card').forEach(card => {
                card.style.background = 'rgba(255,255,255,0.02)';
                card.querySelector('i').style.color = 'var(--text-muted)';
            });
            const selected = document.querySelector('.content-type-card[data-type="'+radio.value+'"]');
            if(selected) { 
                selected.style.background = 'rgba(99,102,241,0.15)'; 
                selected.querySelector('i').style.color = 'var(--primary)'; 
            }
            
            const hint = document.getElementById('file-hint');
            if(radio.value === 'Video') hint.textContent = 'Supported: .mp4, .mov, .avi (Max 100MB)';
            else if(radio.value === 'PDF') hint.textContent = 'Supported: .pdf (Max 20MB)';
            else hint.textContent = 'Upload task or question document.';
        });
    });

    window.onclick = function(event) {
        if (event.target.className === 'custom-modal') {
            event.target.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
</script>

<style>
    .custom-modal { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .custom-modal > div { animation: slideUp 0.4s ease-out; }
    @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    
    .badge-purple {
        background: rgba(168, 85, 247, 0.1);
        color: #a855f7;
        padding: 0.2rem 0.6rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid rgba(168, 85, 247, 0.2);
    }
</style>

@endsection
