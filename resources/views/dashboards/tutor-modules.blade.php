@extends('layouts.dashboard')
@section('title', 'Manage Modules')
@section('content')

{{-- Header with Create Button --}}
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h3 style="margin: 0;">Course Modules</h3>
        <p style="color: var(--text-muted); margin: 0.5rem 0 0 0;">Organize your courses by creating and managing curriculum modules.</p>
    </div>
    <button onclick="openModal('createModuleModal')" class="btn btn-primary" style="padding: 0.8rem 1.5rem; border-radius: 12px;">
        <i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i>Add New Module
    </button>
</div>

@if(session('status'))
<div style="background: rgba(16,185,129,0.15); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #10b981;">
    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ session('status') }}
</div>
@endif

{{-- Modules Table --}}
<div class="glass-card" style="padding: 0; overflow: hidden;">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="text-align:left; color:var(--text-muted); background: rgba(255,255,255,0.02); border-bottom:1px solid var(--glass-border);">
                <th style="padding:1.25rem;">Module Title</th>
                <th style="padding:1.25rem;">Associated Course</th>
                <th style="padding:1.25rem;">Date Created</th>
                <th style="padding:1.25rem; text-align: right;">Lessons</th>
            </tr>
        </thead>
        <tbody>
            @forelse($modules as $mod)
            <tr style="border-bottom:1px solid var(--glass-border); transition: 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                <td style="padding:1.25rem;">
                    <div style="font-weight:600; color: white;">{{ $mod->ModuleTitle }}</div>
                </td>
                <td style="padding:1.25rem; color:var(--text-muted);">
                    {{ $mod->course->Title ?? 'Unassigned Course' }}
                </td>
                <td style="padding:1.25rem; color:var(--text-muted);">
                    {{ $mod->created_at->format('M d, Y') }}
                </td>
                <td style="padding:1.25rem; text-align: right;">
                    <span class="badge badge-purple">{{ $mod->lessons_count ?? $mod->lessons()->count() }} Lessons</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:4rem; text-align:center; color:var(--text-muted);">
                    <i class="fas fa-layer-group" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.5;"></i>
                    No modules found. Start by creating one!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem;">
    {{ $modules->links() }}
</div>

{{-- Create Module Modal --}}
<div id="createModuleModal" class="custom-modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8); backdrop-filter: blur(8px);">
    <div class="glass-card" style="width: 500px; margin: 15vh auto; padding: 2.5rem; position: relative; border: 1px solid var(--primary);">
        <span onclick="closeModal('createModuleModal')" style="position:absolute; top:1rem; right:1.5rem; font-size:1.5rem; cursor:pointer; color:var(--text-muted);">&times;</span>
        
        <h3 style="margin-bottom: 2rem;"><i class="fas fa-layer-group" style="margin-right: 0.75rem; color: var(--primary);"></i>New Course Module</h3>
        
        <form action="{{ route('tutor.modules.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Select Target Course</label>
                <select name="CourseID" required style="width:100%; padding:0.9rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:12px; color:white; outline: none; appearance: none;">
                    <option value="" disabled selected>— Select a course —</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->CourseID }}">{{ $course->Title }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.5rem;">Module Title</label>
                <input type="text" name="ModuleTitle" required placeholder="e.g. Introduction to Database Design" style="width:100%; padding:0.9rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:12px; color:white; outline: none;">
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 1rem; font-weight: 600;">Create Module</button>
                <button type="button" onclick="closeModal('createModuleModal')" class="btn glass-card" style="padding: 1rem 1.5rem;">Cancel</button>
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
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid rgba(168, 85, 247, 0.2);
    }
</style>

@endsection
