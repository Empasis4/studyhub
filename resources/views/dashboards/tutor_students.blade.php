@extends('layouts.dashboard')
@section('title', 'Student Management')
@section('content')

{{-- Header --}}
<div style="margin-bottom: 2rem;">
    <h3 style="margin: 0;"><i class="fas fa-users" style="margin-right: 0.75rem; color: var(--primary);"></i>Student Management</h3>
    <p style="color: var(--text-muted); margin: 0.5rem 0 0 0;">Manage and monitor students across your assigned courses.</p>
</div>

{{-- Course Selector --}}
<div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 1.5rem; margin-bottom: 1rem;">
    <a href="{{ route('tutor.students') }}" 
       class="glass-card {{ !$selectedCourse ? 'active-course' : '' }}" 
       style="min-width: 150px; padding: 1.25rem; text-decoration: none; text-align: center; transition: 0.3s; border: 1px solid {{ !$selectedCourse ? 'var(--primary)' : 'var(--glass-border)' }};">
        <div style="font-size: 1.2rem; margin-bottom: 0.5rem;"><i class="fas fa-globe"></i></div>
        <div style="font-weight: 600; font-size: 0.85rem;">All Courses</div>
        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $courses->count() }} Total</div>
    </a>

    @foreach($courses as $course)
    <a href="{{ route('tutor.students', ['course_id' => $course->CourseID]) }}" 
       class="glass-card {{ $selectedCourse == $course->CourseID ? 'active-course' : '' }}" 
       style="min-width: 200px; padding: 1.25rem; text-decoration: none; transition: 0.3s; border: 1px solid {{ $selectedCourse == $course->CourseID ? 'var(--primary)' : 'var(--glass-border)' }};">
        <div style="font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $course->Title }}</div>
        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $course->enrollment()->count() }} Students Enrolled</div>
    </a>
    @endforeach
</div>

{{-- Students Table --}}
<div class="glass-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
        <h4 style="margin: 0;">{{ $selectedCourse ? 'Course Roster' : 'Global Student Roster' }}</h4>
        <span class="badge badge-purple">{{ $enrollments->total() }} Students</span>
    </div>
    
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="text-align:left; color:var(--text-muted); background: rgba(255,255,255,0.02); border-bottom:1px solid var(--glass-border);">
                <th style="padding:1.25rem;">Student Info</th>
                <th style="padding:1.25rem;">Course</th>
                <th style="padding:1.25rem;">Enrollment Date</th>
                <th style="padding:1.25rem; text-align: right;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($enrollments as $e)
            <tr style="border-bottom:1px solid var(--glass-border); transition: 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                <td style="padding:1.25rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width:40px; height:40px; border-radius:12px; background:linear-gradient(45deg, var(--primary), var(--secondary)); color:white; display:flex; align-items:center; justify-content:center; font-weight:700;">
                            {{ substr($e->student->Name ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight:600; color: white;">{{ $e->student->Name ?? 'Unknown' }}</div>
                            <div style="font-size:0.8rem; color:var(--text-muted);">{{ $e->student->Email ?? '' }}</div>
                        </div>
                    </div>
                </td>
                <td style="padding:1.25rem;">
                    <div style="font-weight:500;">{{ $e->course->Title ?? '—' }}</div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $e->course->category->CategoryName ?? 'General' }}</div>
                </td>
                <td style="padding:1.25rem; color:var(--text-muted);">
                    {{ $e->created_at ? $e->created_at->format('M d, Y') : '—' }}
                </td>
                <td style="padding:1.25rem; text-align: right;">
                    <span class="badge" style="background:rgba(16,185,129,0.1); color:#10b981; padding: 0.4rem 0.8rem; border-radius: 8px;">Active</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:5rem; text-align:center; color:var(--text-muted);">
                    <i class="fas fa-users-slash" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.5;"></i>
                    No students found for this selection.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem;">
    {{ $enrollments->appends(['course_id' => $selectedCourse])->links() }}
</div>

<style>
    .active-course {
        background: rgba(99,102,241,0.1) !important;
        border-color: var(--primary) !important;
    }
    .badge-purple {
        background: rgba(168, 85, 247, 0.1);
        color: #a855f7;
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }
</style>

@endsection
