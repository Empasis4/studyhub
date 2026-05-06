<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Application | StudyHub</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="hero-bg"></div>
    
    <div class="glass-card" style="width: 100%; max-width: 500px; padding: 3rem;">
        <div class="logo" style="margin-bottom: 2rem; justify-content: center; color: var(--secondary);">StudyHub</div>
        <h2 style="text-align: center; margin-bottom: 1rem; font-weight: 700;">Apply as Instructor</h2>
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem; font-size: 0.9rem;">Join our community of expert educators. Professional license required for approval.</p>
        
        <form action="{{ route('register.tutor') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;">Full Name</label>
                <input type="text" name="Name" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: white; outline: none;" placeholder="Full name as on license">
                @error('Name') <p style="color: #f87171; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p> @enderror
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;">Work Email</label>
                <input type="email" name="Email" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: white; outline: none;" placeholder="name@studyhub.com">
                @error('Email') <p style="color: #f87171; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;">Professional License (PDF/Image)</label>
                <div class="glass-card" style="position: relative; padding: 1rem; text-align: center; border-style: dashed; background: rgba(168,85,247,0.05);">
                    <input type="file" name="LicenseFile" required style="position: absolute; inset: 0; opacity: 0; cursor: pointer;">
                    <i class="fas fa-file-upload" style="font-size: 1.5rem; color: var(--secondary); margin-bottom: 0.5rem; display: block;"></i>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Click or drag license file</span>
                </div>
                @error('LicenseFile') <p style="color: #f87171; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p> @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;">Password</label>
                    <input type="password" name="Password" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: white; outline: none;" placeholder="Min. 6 chars">
                </div>
                <div>
                    <label style="display: block; color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;">Confirm</label>
                    <input type="password" name="password_confirmation" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: white; outline: none;" placeholder="Repeat">
                </div>
                @error('Password') <p style="color: #f87171; font-size: 0.8rem; grid-column: span 2;">{{ $message }}</p> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-weight: 600; background: var(--secondary);">Submit Tutor Application</button>
        </form>
        
        <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
            Already an instructor? <a href="{{ route('login') }}" style="color: var(--secondary); text-decoration: none; font-weight: 600;">Sign in here</a>
        </p>
    </div>
</body>
</html>
