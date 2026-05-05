@extends('layouts.dashboard')
@section('title', 'Create Category')
@section('content')
<div class="glass-card" style="max-width: 500px; margin: 0 auto;">
    <h3 style="margin-bottom: 2rem;"><i class="fas fa-layer-group" style="margin-right: 0.75rem; color: var(--primary);"></i>New Category</h3>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; color: #ef4444; font-size: 0.9rem;">
            {{ $errors->first() }}
        </div>
        @endif
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Category Name</label>
            <input type="text" name="CategoryName" required autofocus placeholder="e.g. Web Development"
                   style="width: 100%; padding: 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--text-main); font-size: 1rem;">
        </div>
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('admin.categories') }}" class="btn glass-card" style="text-decoration: none;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                <i class="fas fa-save" style="margin-right: 0.5rem;"></i>Create Category
            </button>
        </div>
    </form>
</div>
@endsection
