@extends('layouts.dashboard')

@section('title', 'Create New Course')

@section('content')
<div class="glass-card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('admin.courses.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div style="grid-column: span 2; margin-bottom: 1rem;">
                <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Course Title</label>
                <input type="text" name="Title" required placeholder="e.g. Advanced Machine Learning" 
                       style="width: 100%; padding: 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); font-size: 1rem;">
            </div>

            <div style="grid-column: span 2; margin-bottom: 1rem;">
                <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Description</label>
                <textarea name="Description" rows="4" placeholder="Tell your students what they will learn..." 
                          style="width: 100%; padding: 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); font-size: 1rem; resize: vertical;"></textarea>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Category</label>
                <select name="CategoryID" required 
                        style="width: 100%; padding: 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); font-size: 1rem; appearance: none;">
                    <option value="" disabled selected>Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->CategoryID }}">{{ $category->CategoryName }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Course Admin</label>
                <select name="AdminID" required 
                        style="width: 100%; padding: 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); font-size: 1rem; appearance: none;">
                    <option value="{{ auth()->id() }}">Me ({{ auth()->user()->Name }})</option>
                    @foreach($admins as $admin)
                        @if($admin->UserID != auth()->id())
                            <option value="{{ $admin->UserID }}">{{ $admin->Name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Price (USD)</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);">$</span>
                    <input type="number" step="0.01" name="Price" required placeholder="0.00" 
                           style="width: 100%; padding: 1rem 1rem 1rem 2rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); font-size: 1rem;">
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Status</label>
                <div style="display: flex; gap: 1rem;">
                    <label class="btn glass-card" style="flex: 1; text-align: center; cursor: pointer; padding: 1rem; background: rgba(16, 185, 129, 0.1);">
                        <input type="radio" name="Status" value="Published" checked style="margin-right: 0.5rem;"> Published
                    </label>
                    <label class="btn glass-card" style="flex: 1; text-align: center; cursor: pointer; padding: 1rem;">
                        <input type="radio" name="Status" value="Draft" style="margin-right: 0.5rem;"> Draft
                    </label>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
            <a href="{{ route('admin.courses') }}" class="btn glass-card" style="text-decoration: none;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1rem;">
                <i class="fas fa-save" style="margin-right: 0.5rem;"></i> Create Course
            </button>
        </div>
    </form>
</div>
@endsection
