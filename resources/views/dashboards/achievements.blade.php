@extends('layouts.dashboard')
@section('title', 'My Achievements')
@section('content')

{{-- Progress Summary --}}
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 3rem;">
    <div class="card-stat" style="text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">{{ $total }}</div>
        <div style="color: var(--text-muted); margin-top: 0.5rem;">Enrolled Courses</div>
    </div>
    <div class="card-stat" style="text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #10b981;">{{ $completed }}</div>
        <div style="color: var(--text-muted); margin-top: 0.5rem;">Courses Completed</div>
    </div>
    <div class="card-stat" style="text-align: center;">
        @php $avgProgress = $total > 0 ? round($enrollments->avg('ProgressPercentage')) : 0; @endphp
        <div style="font-size: 2.5rem; font-weight: 800; color: #fbbf24;">{{ $avgProgress }}%</div>
        <div style="color: var(--text-muted); margin-top: 0.5rem;">Average Progress</div>
    </div>
</div>

{{-- Badges --}}
<div class="glass-card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-medal" style="margin-right: 0.75rem; color: #fbbf24;"></i>Badges Earned</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1.5rem;">
        @php
            $badges = [
                ['icon'=>'fa-user-graduate','label'=>'First Enrollment','earned'=> $total >= 1,'color'=>'#6366f1'],
                ['icon'=>'fa-book-open','label'=>'Bookworm','earned'=> $total >= 3,'color'=>'#a855f7'],
                ['icon'=>'fa-trophy','label'=>'Achiever','earned'=> $completed >= 1,'color'=>'#f59e0b'],
                ['icon'=>'fa-star','label'=>'Gold Student','earned'=> $completed >= 3,'color'=>'#fbbf24'],
                ['icon'=>'fa-chart-line','label'=>'On a Roll','earned'=> $avgProgress >= 50,'color'=>'#10b981'],
                ['icon'=>'fa-fire','label'=>'Dedicated','earned'=> $avgProgress >= 80,'color'=>'#ef4444'],
            ];
        @endphp
        @foreach($badges as $badge)
        <div style="text-align: center; padding: 1.5rem; border: 1px solid {{ $badge['earned'] ? $badge['color'] : 'var(--glass-border)' }}; border-radius: 16px; background: {{ $badge['earned'] ? 'rgba('.hexdec(substr($badge['color'],1,2)).','.hexdec(substr($badge['color'],3,2)).','.hexdec(substr($badge['color'],5,2)).',0.08)' : 'rgba(255,255,255,0.01)' }}; transition: 0.3s; {{ $badge['earned'] ? '' : 'opacity:0.4;' }}">
            <div style="font-size: 2rem; color: {{ $badge['earned'] ? $badge['color'] : 'var(--text-muted)' }}; margin-bottom: 0.75rem;">
                <i class="fas {{ $badge['icon'] }}"></i>
            </div>
            <div style="font-weight: 600; font-size: 0.9rem;">{{ $badge['label'] }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">{{ $badge['earned'] ? 'Earned!' : 'Locked' }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Course Progress --}}
<div class="glass-card">
    <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-tasks" style="margin-right: 0.75rem; color: var(--primary);"></i>Course Progress</h3>
    @forelse($enrollments as $e)
    <div style="margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="font-weight: 600;">{{ $e->course->Title ?? 'Course' }}</span>
            <span style="font-size: 0.85rem; color: {{ $e->ProgressPercentage >= 100 ? '#10b981' : 'var(--text-muted)' }};">
                @if($e->ProgressPercentage >= 100) <i class="fas fa-check-circle"></i> Completed @else {{ $e->ProgressPercentage }}% @endif
            </span>
        </div>
        <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 10px;">
            <div style="width: {{ $e->ProgressPercentage }}%; height: 100%; background: {{ $e->ProgressPercentage >= 100 ? '#10b981' : 'linear-gradient(90deg, var(--primary), var(--secondary))' }}; border-radius: 10px; transition: width 1s;"></div>
        </div>
    </div>
    @empty
    <p style="color: var(--text-muted); text-align: center; padding: 2rem;">Enroll in courses to track your progress here!</p>
    @endforelse
</div>
@endsection
