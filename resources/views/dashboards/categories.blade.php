@extends('layouts.dashboard')
@section('title', 'Manage Categories')
@section('content')
@if(session('status'))
<div style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #10b981;">
    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ session('status') }}
</div>
@endif
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3><i class="fas fa-layer-group" style="margin-right: 0.75rem; color: var(--primary);"></i>Course Categories</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="padding: 0.6rem 1.5rem; text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>New Category
        </a>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem;">
        @forelse($categories as $cat)
        <div class="glass-card" style="background: rgba(255,255,255,0.02); text-align: center; padding: 2rem 1.5rem;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">
                <i class="fas fa-folder-open"></i>
            </div>
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">{{ $cat->CategoryName }}</div>
            <div style="color: var(--text-muted); font-size: 0.85rem;">{{ $cat->courses_count }} course{{ $cat->courses_count != 1 ? 's' : '' }}</div>
        </div>
        @empty
        <div style="grid-column: span 4; padding: 3rem; text-align: center; color: var(--text-muted);">No categories yet. Create your first one!</div>
        @endforelse
    </div>
    <div style="margin-top: 2rem;">{{ $categories->links() }}</div>
</div>
@endsection
