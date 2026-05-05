@extends('layouts.dashboard')

@section('title', 'Create New Curricular Module')

@section('content')
<div class="glass-card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('tutor.modules.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div style="grid-column: span 2; margin-bottom: 1rem;">
                <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Module Title</label>
                <input type="text" name="ModuleName" required placeholder="e.g. Introduction to Neural Networks" 
                       style="width: 100%; padding: 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); font-size: 1rem;">
            </div>

            <div style="grid-column: span 2; margin-bottom: 1rem;">
                <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Select Course</label>
                <select name="CourseID" required 
                        style="width: 100%; padding: 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); font-size: 1rem; appearance: none;">
                    <option value="" disabled selected>Select the course to add this module to</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->CourseID }}">{{ $course->Title }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Sequence Order</label>
                <input type="number" name="Order" placeholder="e.g. 1" 
                       style="width: 100%; padding: 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); font-size: 1rem;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Time Estimation (Hours)</label>
                <input type="number" name="EstimatedHours" placeholder="e.g. 5" 
                       style="width: 100%; padding: 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); font-size: 1rem;">
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
            <a href="{{ route('tutor.courses') }}" class="btn glass-card" style="text-decoration: none;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1rem;">
                <i class="fas fa-layer-group" style="margin-right: 0.5rem;"></i> Create Module
            </button>
        </div>
    </form>
</div>

<div class="glass-card" style="margin-top: 3rem; background: rgba(99, 102, 241, 0.05);">
    <div style="display: flex; gap: 1.5rem; align-items: center;">
        <div style="font-size: 2rem; color: var(--primary);"><i class="fas fa-lightbulb"></i></div>
        <div>
            <h4 style="margin-bottom: 0.25rem;">Tutor Tip</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Organizing your course into logical modules helps students stay motivated and track their progress effectively.</p>
        </div>
    </div>
</div>
@endsection
