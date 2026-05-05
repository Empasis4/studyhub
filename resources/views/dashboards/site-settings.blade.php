@extends('layouts.dashboard')
@section('title', 'Site Settings')
@section('content')
@if(session('status'))
<div style="background: rgba(16,185,129,0.15); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #10b981;">
    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ session('status') }}
</div>
@endif
<div class="glass-card" style="max-width: 700px; margin: 0 auto;">
    <h3 style="margin-bottom: 2rem;"><i class="fas fa-cog" style="margin-right: 0.75rem; color: var(--primary);"></i>Platform Configuration</h3>
    <form action="{{ route('admin.settings.save') }}" method="POST">
        @csrf
        <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--glass-border);">
            <h4 style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.5rem;">General</h4>
            <div style="margin-bottom: 1.2rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.9rem; margin-bottom:0.4rem;">Platform Name</label>
                <input type="text" name="platform_name" value="StudyHub" style="width:100%; padding:0.9rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:12px; color:var(--text-main); font-size:1rem;">
            </div>
            <div style="margin-bottom: 1.2rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.9rem; margin-bottom:0.4rem;">Contact Email</label>
                <input type="email" name="contact_email" value="admin@studyhub.com" style="width:100%; padding:0.9rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:12px; color:var(--text-main); font-size:1rem;">
            </div>
        </div>

        <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--glass-border);">
            <h4 style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.5rem;">Enrollment</h4>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: rgba(255,255,255,0.02); border-radius: 12px; margin-bottom: 0.75rem;">
                <div>
                    <div style="font-weight: 600;">Allow Free Enrollments</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Let students enroll in $0 courses without payment</div>
                </div>
                <label style="position: relative; display: inline-block; width: 48px; height: 26px; cursor: pointer;">
                    <input type="checkbox" name="free_enrollment" checked style="opacity:0; width:0; height:0;">
                    <span style="position:absolute; inset:0; background:var(--primary); border-radius:26px;"></span>
                    <span style="position:absolute; height:20px; width:20px; left:3px; bottom:3px; background:white; border-radius:50%; transition:0.3s;"></span>
                </label>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: rgba(255,255,255,0.02); border-radius: 12px;">
                <div>
                    <div style="font-weight: 600;">Require Admin Approval for Courses</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">New courses need approval before going live</div>
                </div>
                <label style="position: relative; display: inline-block; width: 48px; height: 26px; cursor: pointer;">
                    <input type="checkbox" name="require_approval" style="opacity:0; width:0; height:0;">
                    <span style="position:absolute; inset:0; background:rgba(255,255,255,0.1); border-radius:26px;"></span>
                    <span style="position:absolute; height:20px; width:20px; left:3px; bottom:3px; background:white; border-radius:50%; transition:0.3s;"></span>
                </label>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem;">
            <button type="reset" class="btn glass-card">Reset</button>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                <i class="fas fa-save" style="margin-right: 0.5rem;"></i>Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
