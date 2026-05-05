@extends('layouts.dashboard')

@section('title', 'Feature Under Construction')

@section('content')
<div class="glass-card" style="text-align: center; padding: 5rem 2rem;">
    <div style="font-size: 5rem; margin-bottom: 2rem; background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">
        <i class="fas fa-tools"></i>
    </div>
    <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Coming Soon!</h2>
    <p style="color: var(--text-muted); font-size: 1.2rem; max-width: 600px; margin: 0 auto 3rem;">
        We're working hard to bring this feature to life. Stay tuned for updates as we continue to enhance your StudyHub experience.
    </p>
    <a href="{{ url()->previous() }}" class="btn btn-primary" style="text-decoration: none;">
        <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i> Go Back
    </a>
</div>
@endsection
