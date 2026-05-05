@extends('layouts.dashboard')
@section('title', 'Add New Lesson')
@section('content')
<div class="glass-card" style="max-width: 650px; margin: 0 auto;">
    <h3 style="margin-bottom: 2rem;"><i class="fas fa-plus-circle" style="margin-right: 0.75rem; color: var(--primary);"></i>Add a New Lesson</h3>
    <form action="{{ route('tutor.lessons.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($errors->any())
        <div style="background: rgba(239,68,68,0.1); border: 1px solid #ef4444; border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; color: #ef4444; font-size: 0.9rem;">{{ $errors->first() }}</div>
        @endif

        <div style="margin-bottom: 1.5rem;">
            <label style="display:block; color:var(--text-muted); font-size:0.9rem; margin-bottom:0.5rem;">Select Module</label>
            <select name="ModuleID" required style="width:100%; padding:1rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:12px; color:var(--text-main); font-size:1rem; appearance:none;">
                <option value="" disabled selected>Choose a module…</option>
                @foreach($modules as $mod)
                <option value="{{ $mod->ModuleID }}">{{ $mod->course->Title ?? 'N/A' }} › {{ $mod->ModuleTitle }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display:block; color:var(--text-muted); font-size:0.9rem; margin-bottom:0.5rem;">Content Type</label>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem;">
                @foreach(['Video','PDF','Quiz'] as $type)
                <label style="cursor: pointer;">
                    <input type="radio" name="ContentType" value="{{ $type }}" {{ $type === 'Video' ? 'checked' : '' }} style="display:none;" class="content-radio">
                    <div class="content-type-card" data-type="{{ $type }}" style="padding: 1rem; border: 1px solid var(--glass-border); border-radius: 12px; text-align: center; transition: 0.3s; background: {{ $type === 'Video' ? 'rgba(99,102,241,0.15)' : 'var(--glass)' }};">
                        <i class="fas fa-{{ $type === 'Video' ? 'video' : ($type === 'PDF' ? 'file-pdf' : 'question-circle') }}" style="font-size: 1.5rem; margin-bottom: 0.5rem; color: {{ $type === 'Video' ? 'var(--primary)' : 'var(--text-muted)' }};"></i>
                        <div style="font-size: 0.9rem; font-weight: 600;">{{ $type }}</div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div id="file-input-container" style="margin-bottom: 2rem;">
            <label style="display:block; color:var(--text-muted); font-size:0.9rem; margin-bottom:0.5rem;">Upload Lesson Content</label>
            <input type="file" name="LessonFile" required id="lesson_file"
                   style="width:100%; padding:1rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:12px; color:var(--text-main); font-size:1rem;">
            <p id="file-hint" style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Supported formats: .mp4, .mov, .pdf (Max 50MB)</p>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('tutor.courses') }}" class="btn glass-card" style="text-decoration: none;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                <i class="fas fa-save" style="margin-right: 0.5rem;"></i>Save Lesson
            </button>
        </div>
    </form>
</div>
<script>
document.querySelectorAll('.content-radio').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.content-type-card').forEach(card => {
            card.style.background = 'var(--glass)';
            card.querySelector('i').style.color = 'var(--text-muted)';
        });
        const selected = document.querySelector('.content-type-card[data-type="'+radio.value+'"]');
        if(selected) { 
            selected.style.background = 'rgba(99,102,241,0.15)'; 
            selected.querySelector('i').style.color = 'var(--primary)'; 
        }

        // Update Hint and Accept attribute
        const hint = document.getElementById('file-hint');
        const input = document.getElementById('lesson_file');
        if(radio.value === 'Video') {
            hint.textContent = 'Supported formats: .mp4, .mov, .avi (Max 100MB)';
            input.setAttribute('accept', 'video/*');
        } else if(radio.value === 'PDF') {
            hint.textContent = 'Supported formats: .pdf (Max 20MB)';
            input.setAttribute('accept', '.pdf');
        } else {
            hint.textContent = 'Upload question set or task document.';
            input.removeAttribute('accept');
        }
    });
});
</script>
@endsection
