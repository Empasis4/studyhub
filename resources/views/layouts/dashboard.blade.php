<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | StudyHub</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { display: flex; }
        .sidebar {
            width: 280px;
            height: 100vh;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            padding: 2rem;
            position: fixed;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            margin-left: 280px;
            width: calc(100% - 280px);
            padding: 3rem;
            min-height: 100vh;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
            margin-bottom: 0.5rem;
        }
        .nav-item:hover, .nav-item.active {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--glass);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            border: 1px solid var(--glass-border);
        }
        .card-stat {
            background: var(--glass);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid var(--glass-border);
        }
    </style>
</head>
<body>
    <div class="hero-bg"></div>
    
    <div class="sidebar">
        <div class="logo" style="margin-bottom: 3rem;">StudyHub</div>
        
        <div style="flex: 1;">
            <a href="{{ route(auth()->user()->role->RoleName == 'Super Admin' ? 'super.admin.dashboard' : (auth()->user()->role->RoleName == 'Admin' ? 'admin.dashboard' : (auth()->user()->role->RoleName == 'Tutor' ? 'tutor.dashboard' : 'student.dashboard'))) }}" class="nav-item {{ Request::is('super-admin', 'admin', 'tutor', 'student') ? 'active' : '' }}"><i class="fas fa-home"></i> Overview</a>
            
            @if(auth()->user()->role->RoleName == 'Super Admin')
                <a href="{{ route('super.admin.users') }}" class="nav-item {{ Request::is('*/users') ? 'active' : '' }}"><i class="fas fa-users-cog"></i> Manage Users</a>
                <a href="{{ route('super.admin.logs') }}" class="nav-item {{ Request::is('*/logs') ? 'active' : '' }}"><i class="fas fa-history"></i> System Logs</a>
            @endif

            @if(auth()->user()->role->RoleName == 'Admin')
                <a href="{{ route('admin.courses') }}" class="nav-item {{ Request::is('*/courses*') ? 'active' : '' }}"><i class="fas fa-book"></i> Courses</a>
                <a href="{{ route('admin.categories') }}" class="nav-item {{ Request::is('*/categories*') ? 'active' : '' }}"><i class="fas fa-layer-group"></i> Categories</a>
                <a href="{{ route('admin.tutors.assign') }}" class="nav-item {{ Request::is('*/assign-tutor*') ? 'active' : '' }}"><i class="fas fa-user-plus"></i> Assign Tutor</a>
                <a href="{{ route('admin.analytics') }}" class="nav-item {{ Request::is('*/analytics') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Analytics</a>
                <a href="{{ route('admin.reports.export') }}" class="nav-item {{ Request::is('*/export-reports') ? 'active' : '' }}"><i class="fas fa-file-export"></i> Reports</a>
                <a href="{{ route('admin.settings') }}" class="nav-item {{ Request::is('*/site-settings') ? 'active' : '' }}"><i class="fas fa-cog"></i> Settings</a>
            @endif

            @if(auth()->user()->role->RoleName == 'Tutor')
                <a href="{{ route('tutor.courses') }}" class="nav-item {{ Request::is('*/my-courses') ? 'active' : '' }}"><i class="fas fa-chalkboard-teacher"></i> My Courses</a>
                <a href="{{ route('tutor.modules.create') }}" class="nav-item {{ Request::is('*/modules/create') ? 'active' : '' }}"><i class="fas fa-layer-group"></i> New Module</a>
                <a href="{{ route('tutor.lessons.add') }}" class="nav-item {{ Request::is('*/lessons/add') ? 'active' : '' }}"><i class="fas fa-play-circle"></i> Add Lesson</a>
                <a href="{{ route('tutor.assessments') }}" class="nav-item {{ Request::is('*/assessments*') ? 'active' : '' }}"><i class="fas fa-file-invoice"></i> Assessments</a>
                <a href="{{ route('tutor.students') }}" class="nav-item {{ Request::is('*/students') ? 'active' : '' }}"><i class="fas fa-users"></i> Enrolled Students</a>
            @endif

            @if(auth()->user()->role->RoleName == 'Student')
                <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a>
                <a href="{{ route('student.browse') }}" class="nav-item {{ request()->routeIs('student.browse') ? 'active' : '' }}"><i class="fas fa-search"></i> Explore Courses</a>
                <a href="{{ route('student.assessments') }}" class="nav-item {{ request()->routeIs('student.assessments') ? 'active' : '' }}"><i class="fas fa-tasks"></i> My Assessments</a>
                <a href="{{ route('student.learning') }}" class="nav-item {{ request()->routeIs('student.learning') ? 'active' : '' }}"><i class="fas fa-graduation-cap"></i> My Learning</a>
            @endif

            <a href="{{ route('notifications') }}" class="nav-item {{ Request::is('notifications') ? 'active' : '' }}"><i class="fas fa-bell"></i> Notifications</a>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-item" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h1>@yield('title', 'Dashboard')</h1>
            <div class="user-profile">
                <div style="text-align: right;">
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ auth()->user()->Name }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ auth()->user()->role->RoleName }}</div>
                </div>
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700;">
                    {{ substr(auth()->user()->Name, 0, 1) }}
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <div id="notification-toast" style="position:fixed;bottom:2rem;right:2rem;background:var(--primary);color:white;padding:1rem 2rem;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.3);display:none;z-index:9999;animation:slideIn 0.5s ease-out;">
        <div style="display:flex;align-items:center;gap:1rem;">
            <i class="fas fa-bell"></i>
            <span id="notification-msg">New notification received!</span>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>

    <script>
        let lastCount = 0;
        function checkNotifications() {
            fetch('{{ route('api.notifications.latest') }}')
                .then(response => response.json())
                .then(data => {
                    // Update bell badge count
                    const badge = document.getElementById('notif-badge');
                    const unread = data.filter(n => !n.IsRead).length;
                    if (badge) {
                        badge.innerText = unread;
                        badge.style.display = unread > 0 ? 'flex' : 'none';
                    }
                    // Show toast if new notification arrived
                    if (data.length > lastCount && lastCount !== 0) {
                        const toast = document.getElementById('notification-toast');
                        const msg = document.getElementById('notification-msg');
                        if (toast && msg) {
                            msg.innerText = data[0].Message;
                            toast.style.display = 'block';
                            setTimeout(() => { toast.style.display = 'none'; }, 5000);
                        }
                    }
                    lastCount = data.length;
                })
                .catch(() => {}); // Silently ignore errors
        }
        // Initial check after 1s, then every 15s (safe for PHP dev server)
        setTimeout(checkNotifications, 1000);
        setInterval(checkNotifications, 15000);

        // Mark as read function
        function markAsRead(id, element) {
            fetch(`/notifications/mark-as-read/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                if(element) element.style.opacity = '0.5';
            }).catch(() => {});
        }
    </script>
</body>
</html>
