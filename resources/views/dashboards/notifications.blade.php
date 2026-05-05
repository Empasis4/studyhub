@extends('layouts.dashboard')
@section('title', 'Notifications')
@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3><i class="fas fa-bell" style="margin-right: 0.75rem; color: var(--primary);"></i>Notifications</h3>
        <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $notifications->where('IsRead', false)->count() }} unread</span>
    </div>

    @forelse($notifications as $notif)
    <div id="notif-{{ $notif->NotifyID }}" style="display: flex; gap: 1rem; padding: 1.25rem; border-radius: 14px; margin-bottom: 0.75rem; background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); transition: 0.3s; cursor: pointer; opacity: {{ $notif->IsRead ? '0.5' : '1' }};"
         onclick="markAsRead({{ $notif->NotifyID }}, this)"
         onmouseover="this.style.background='rgba(99,102,241,0.05)'" onmouseout="this.style.background='rgba(255,255,255,0.02)'">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, {{ $notif->IsRead ? 'var(--text-muted)' : 'var(--primary)' }}, var(--secondary)); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="fas fa-{{ $notif->IsRead ? 'check' : 'bell' }}" style="font-size: 0.85rem;"></i>
        </div>
        <div style="flex: 1;">
            <div style="font-size: 0.95rem; margin-bottom: 0.25rem;">{{ $notif->Message }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Just now' }}</div>
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 5rem 2rem;">
        <div style="font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.3;"><i class="fas fa-bell-slash"></i></div>
        <h3 style="margin-bottom: 0.5rem;">All caught up!</h3>
        <p style="color: var(--text-muted);">No notifications yet. We'll let you know when something happens.</p>
    </div>
    @endforelse

    <div style="margin-top: 1.5rem;">{{ $notifications->links() }}</div>
</div>
@endsection
