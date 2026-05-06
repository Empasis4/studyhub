@extends('layouts.dashboard')
@section('title', 'Create New Module')
@section('content')

<div class="glass-card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="margin-bottom: 2rem;"><i class="fas fa-layer-group" style="margin-right: 0.75rem; color: var(--secondary);"></i>New Course Module</h3>
    
    <form action="{{ route('tutor.modules.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;">Select Target Course</label>
            <select name="CourseID" required style="width: 100%; padding: 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: white; outline: none; appearance: none;">
                <option value="" disabled selected>— Select a course —</option>
                @foreach($courses as $course)
                    <option value="{{ $course->CourseID }}">{{ $course->Title }}</option>
                @endforeach
            </select>
            @error('CourseID') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p> @enderror
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;">Module Title</label>
            <input type="text" name="ModuleTitle" required style="width: 100%; padding: 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: white; outline: none;" placeholder="e.g. Introduction to Database Design">
            @error('ModuleTitle') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p> @enderror
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1; padding: 1rem; background: var(--secondary);">Create Module</button>
            <a href="{{ route('tutor.courses') }}" class="btn glass-card" style="padding: 1rem 2rem; text-decoration: none; text-align: center;">Cancel</a>
        </div>
    </form>
</div>

@endsection
