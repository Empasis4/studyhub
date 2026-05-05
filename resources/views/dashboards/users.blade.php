@extends('layouts.dashboard')

@section('title', 'Manage Users')

@section('content')
@if(session('status'))
<div style="background: rgba(16,185,129,0.15); border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #10b981;">
    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ session('status') }}
</div>
@endif
@if(session('error'))
<div style="background: rgba(239,68,68,0.15); border: 1px solid #ef4444; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; color: #ef4444;">
    <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>{{ session('error') }}
</div>
@endif

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">
    {{-- Create User Form --}}
    <div class="glass-card">
        <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-user-plus" style="margin-right: 0.75rem; color: var(--primary);"></i>Create New User</h3>
        <form action="{{ route('super.admin.users.store') }}" method="POST">
            @csrf
            @if($errors->any())
            <div style="background: rgba(239,68,68,0.1); border: 1px solid #ef4444; border-radius: 12px; padding: 1rem; margin-bottom: 1rem; color: #ef4444; font-size: 0.9rem;">{{ $errors->first() }}</div>
            @endif
            <div style="margin-bottom: 1.2rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.4rem;">Full Name</label>
                <input type="text" name="Name" required placeholder="John Doe" style="width:100%; padding:0.9rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:12px; color:var(--text-main); font-size:0.95rem;">
            </div>
            <div style="margin-bottom: 1.2rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.4rem;">Email</label>
                <input type="email" name="Email" required placeholder="user@studyhub.com" style="width:100%; padding:0.9rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:12px; color:var(--text-main); font-size:0.95rem;">
            </div>
            <div style="margin-bottom: 1.2rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.4rem;">Password</label>
                <input type="password" name="Password" required placeholder="Min. 6 characters" style="width:100%; padding:0.9rem; background:var(--glass); border:1px solid var(--glass-border); border-radius:12px; color:var(--text-main); font-size:0.95rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.4rem;">Role</label>
                <select name="RoleID" required style="width:100%; padding:0.9rem; background:var(--bg-dark); border:1px solid var(--glass-border); border-radius:12px; color:var(--text-main); font-size:0.95rem;">
                    @foreach($roles as $role)
                        <option value="{{ $role->RoleID }}">{{ $role->RoleName }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding:0.85rem;">
                <i class="fas fa-plus" style="margin-right:0.5rem;"></i>Create User
            </button>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3>Platform Users ({{ $users->total() }})</h3>
        </div>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 1rem;">Name</th>
                    <th style="padding: 1rem;">Email</th>
                    <th style="padding: 1rem;">Role</th>
                    <th style="padding: 1rem;">Status</th>
                    <th style="padding: 1rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid var(--glass-border);">
                    <td style="padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700;">
                                {{ substr($user->Name, 0, 1) }}
                            </div>
                            {{ $user->Name }}
                        </div>
                    </td>
                    <td style="padding: 1rem; color: var(--text-muted);">{{ $user->Email }}</td>
                    <td style="padding: 1rem;">
                        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                            {{ $user->role->RoleName }}
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        @if($user->Status == 'Active')
                            <span style="color: #10b981; font-size: 0.8rem;"><i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.4rem;"></i> Active</span>
                        @else
                            <span style="color: #ef4444; font-size: 0.8rem;"><i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.4rem;"></i> Inactive</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <form action="{{ route('super.admin.users.toggle', $user->UserID) }}" method="POST">
                                @csrf
                                <button type="submit" title="{{ $user->Status == 'Active' ? 'Deactivate' : 'Activate' }}" style="background: none; border: 1px solid var(--glass-border); color: {{ $user->Status == 'Active' ? '#fbbf24' : '#10b981' }}; padding: 0.4rem 0.6rem; border-radius: 8px; cursor: pointer; transition: 0.3s;">
                                    <i class="fas fa-{{ $user->Status == 'Active' ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            @if($user->UserID !== auth()->user()->UserID)
                            <form action="{{ route('super.admin.users.delete', $user->UserID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete" style="background: none; border: 1px solid var(--glass-border); color: #ef4444; padding: 0.4rem 0.6rem; border-radius: 8px; cursor: pointer; transition: 0.3s;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 2rem;">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
