@extends('layouts.dashboard')
@section('title', 'Pending Tutor Applications')
@section('content')

<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3><i class="fas fa-user-shield" style="margin-right: 0.75rem; color: var(--secondary);"></i>Tutor Verification Queue</h3>
        <span class="badge badge-purple">{{ count($tutors) }} Applications</span>
    </div>

    @if(session('status'))
        <div style="background: rgba(16,185,129,0.15); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #10b981;">
            {{ session('status') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 1rem;">Applicant</th>
                    <th style="padding: 1rem;">Email</th>
                    <th style="padding: 1rem;">License / Credentials</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tutors as $tutor)
                <tr style="border-bottom: 1px solid var(--glass-border);">
                    <td style="padding: 1rem; font-weight: 600;">{{ $tutor->Name }}</td>
                    <td style="padding: 1rem; color: var(--text-muted);">{{ $tutor->Email }}</td>
                    <td style="padding: 1rem;">
                        <a href="{{ asset('storage/' . $tutor->LicenseURL) }}" target="_blank" class="btn glass-card" style="font-size: 0.8rem; padding: 0.5rem 0.75rem; color: var(--secondary);">
                            <i class="fas fa-id-card" style="margin-right: 0.4rem;"></i>View License
                        </a>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                            <form action="{{ route('super.admin.tutors.approve', $tutor->UserID) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="background: #10b981; font-size: 0.85rem; padding: 0.5rem 1rem;">Approve</button>
                            </form>
                            <form action="{{ route('super.admin.tutors.reject', $tutor->UserID) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this application?')">
                                @csrf
                                <button type="submit" class="btn glass-card" style="color: #ef4444; border-color: rgba(239,68,68,0.2); font-size: 0.85rem; padding: 0.5rem 1rem;">Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                        <i class="fas fa-check-double" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                        All caught up! No pending applications.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
