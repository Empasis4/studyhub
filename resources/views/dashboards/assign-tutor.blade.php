@extends('layouts.dashboard')
@section('title', 'Assign Tutors to Modules')
@section('content')
@if(session('status'))
<div style="background: rgba(16,185,129,0.15); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #10b981;">
    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ session('status') }}
</div>
@endif

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">
    {{-- Assignment Form --}}
    <div class="glass-card">
        <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-user-plus" style="margin-right: 0.75rem; color: var(--primary);"></i>Assign a Tutor</h3>
        <form action="{{ route('admin.tutors.store') }}" method="POST">
            @csrf
            @if($errors->any())
            <div style="background: rgba(239,68,68,0.1); border: 1px solid #ef4444; border-radius: 12px; padding: 1rem; margin-bottom: 1rem; color: #ef4444; font-size: 0.9rem;">{{ $errors->first() }}</div>
            @endif
            <div style="margin-bottom: 1.2rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.4rem;">Select Module</label>
                <select name="ModuleID" required style="width:100%; padding:0.9rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:12px; color:var(--text-main); font-size:0.95rem; appearance:none;">
                    <option value="" disabled selected>Choose module…</option>
                    @foreach($modules as $mod)
                    <option value="{{ $mod->ModuleID }}">{{ $mod->course->Title ?? 'N/A' }} › {{ $mod->ModuleTitle }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.4rem;">Select Tutor</label>
                <select name="TutorID" required style="width:100%; padding:0.9rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:12px; color:var(--text-main); font-size:0.95rem; appearance:none;">
                    <option value="" disabled selected>Choose tutor…</option>
                    @foreach($tutors as $tutor)
                    <option value="{{ $tutor->UserID }}">{{ $tutor->Name }} ({{ $tutor->Email }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding:0.85rem;">
                <i class="fas fa-link" style="margin-right:0.5rem;"></i>Assign Tutor
            </button>
        </form>
    </div>

    {{-- Current Assignments --}}
    <div class="glass-card">
        <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-list" style="margin-right: 0.75rem; color: var(--secondary);"></i>Current Module Assignments</h3>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="text-align:left; color:var(--text-muted); border-bottom:1px solid var(--glass-border);">
                    <th style="padding:0.75rem 1rem;">Module</th>
                    <th style="padding:0.75rem 1rem;">Course</th>
                    <th style="padding:0.75rem 1rem;">Assigned Tutor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modules as $mod)
                <tr style="border-bottom:1px solid var(--glass-border);">
                    <td style="padding:0.75rem 1rem; font-weight:600;">{{ $mod->ModuleTitle }}</td>
                    <td style="padding:0.75rem 1rem; color:var(--text-muted); font-size:0.9rem;">{{ $mod->course->Title ?? '—' }}</td>
                    <td style="padding:0.75rem 1rem;">
                        @if($mod->tutor)
                        <span style="background:rgba(99,102,241,0.1); color:var(--primary); padding:0.25rem 0.75rem; border-radius:20px; font-size:0.8rem;">{{ $mod->tutor->Name }}</span>
                        @else
                        <span style="color:var(--text-muted); font-size:0.85rem;">Unassigned</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" style="padding:2rem; text-align:center; color:var(--text-muted);">No modules found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top:1.5rem;">{{ $modules->links() }}</div>
    </div>
</div>
@endsection
