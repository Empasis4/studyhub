@extends('layouts.dashboard')

@section('title', 'Manage Courses')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3>Course Catalog</h3>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; text-decoration: none;">+ Create New Course</a>
    </div>
    
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--glass-border);">
                <th style="padding: 1rem;">Course Title</th>
                <th style="padding: 1rem;">Category</th>
                <th style="padding: 1rem;">Admin</th>
                <th style="padding: 1rem;">Price</th>
                <th style="padding: 1rem;">Students</th>
                <th style="padding: 1rem;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr style="border-bottom: 1px solid var(--glass-border);">
                <td style="padding: 1rem; font-weight: 600;">{{ $course->Title }}</td>
                <td style="padding: 1rem;">
                    <span class="badge badge-purple">{{ $course->category->CategoryName ?? 'Uncategorized' }}</span>
                </td>
                <td style="padding: 1rem; color: var(--text-muted);">{{ $course->admin->Name ?? 'N/A' }}</td>
                <td style="padding: 1rem;">${{ number_format($course->Price, 2) }}</td>
                <td style="padding: 1rem;">{{ $course->enrollments_count }}</td>
                <td style="padding: 1rem;">
                    <div style="display: flex; gap: 0.5rem;">
                        <button style="background: none; border: 1px solid var(--glass-border); color: var(--text-muted); padding: 0.4rem; border-radius: 8px; cursor: pointer;"><i class="fas fa-edit"></i></button>
                        <button style="background: none; border: 1px solid var(--glass-border); color: #ef4444; padding: 0.4rem; border-radius: 8px; cursor: pointer;"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 2rem;">
        {{ $courses->links() }}
    </div>
</div>
@endsection
