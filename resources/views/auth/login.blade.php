<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | StudyHub</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="hero-bg"></div>
    
    <div class="glass-card" style="width: 100%; max-width: 450px; padding: 3rem;">
        <div class="logo" style="margin-bottom: 2rem; justify-content: center;">
            StudyHub
        </div>
        
        <h2 style="text-align: center; margin-bottom: 2rem; font-weight: 700;">Welcome Back</h2>
        
        <form action="/login" method="POST">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;">Email Address</label>
                <input type="email" name="Email" required style="width: 100%; padding: 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: white; outline: none;" placeholder="name@example.com">
                @error('Email')
                    <p style="color: #f87171; font-size: 0.8rem; mt: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.9rem;">Password</label>
                <input type="password" name="Password" required style="width: 100%; padding: 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: white; outline: none;" placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">Sign In</button>
        </form>
        
        <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
            Don't have an account? <a href="#" style="color: var(--primary); text-decoration: none;">Join now</a>
        </p>
    </div>
</body>
</html>
