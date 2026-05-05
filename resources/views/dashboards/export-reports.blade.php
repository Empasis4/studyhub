@extends('layouts.dashboard')
@section('title', 'Export Reports')
@section('content')
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 3rem;">
    <div class="glass-card" style="text-align: center; padding: 2.5rem;">
        <div style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"><i class="fas fa-users"></i></div>
        <h3 style="margin-bottom: 0.5rem;">Users Report</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">{{ count($data['users']) }} users total</p>
        <button onclick="exportCSV('users')" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
            <i class="fas fa-download" style="margin-right: 0.5rem;"></i>Download CSV
        </button>
    </div>
    <div class="glass-card" style="text-align: center; padding: 2.5rem;">
        <div style="font-size: 3rem; color: var(--secondary); margin-bottom: 1rem;"><i class="fas fa-book"></i></div>
        <h3 style="margin-bottom: 0.5rem;">Courses Report</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">{{ count($data['courses']) }} courses total</p>
        <button onclick="exportCSV('courses')" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
            <i class="fas fa-download" style="margin-right: 0.5rem;"></i>Download CSV
        </button>
    </div>
    <div class="glass-card" style="text-align: center; padding: 2.5rem;">
        <div style="font-size: 3rem; color: var(--accent); margin-bottom: 1rem;"><i class="fas fa-graduation-cap"></i></div>
        <h3 style="margin-bottom: 0.5rem;">Enrollment Report</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">{{ count($data['enrolled']) }} enrollments</p>
        <button onclick="exportCSV('enrolled')" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
            <i class="fas fa-download" style="margin-right: 0.5rem;"></i>Download CSV
        </button>
    </div>
</div>

<div class="glass-card">
    <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-table" style="margin-right: 0.75rem; color: var(--primary);"></i>Enrollment Data Preview</h3>
    <table style="width: 100%; border-collapse: collapse;" id="enrolled-table">
        <thead>
            <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--glass-border);">
                <th style="padding: 0.75rem 1rem;">Student</th>
                <th style="padding: 0.75rem 1rem;">Course</th>
                <th style="padding: 0.75rem 1rem;">Progress</th>
                <th style="padding: 0.75rem 1rem;">Payment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['enrolled'] as $enroll)
            <tr style="border-bottom: 1px solid var(--glass-border);">
                <td style="padding: 0.75rem 1rem;">{{ $enroll->student->Name ?? 'N/A' }}</td>
                <td style="padding: 0.75rem 1rem;">{{ $enroll->course->Title ?? 'N/A' }}</td>
                <td style="padding: 0.75rem 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="flex: 1; height: 6px; background: rgba(255,255,255,0.05); border-radius: 10px;">
                            <div style="width: {{ $enroll->ProgressPercentage }}%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 10px;"></div>
                        </div>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $enroll->ProgressPercentage }}%</span>
                    </div>
                </td>
                <td style="padding: 0.75rem 1rem;">
                    <span style="background: rgba(16,185,129,0.1); color: #10b981; padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.8rem;">{{ $enroll->PaymentStatus }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">No enrollment data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function exportCSV(type) {
    const datasets = {
        users: {!! json_encode($data['users']->map(fn($u) => [$u->Name, $u->Email, $u->role->RoleName ?? '', $u->Status])->toArray()) !!},
        courses: {!! json_encode($data['courses']->map(fn($c) => [$c->Title, $c->category->CategoryName ?? '', '$'.$c->Price, $c->enrollments_count.' students'])->toArray()) !!},
        enrolled: {!! json_encode($data['enrolled']->map(fn($e) => [$e->student->Name ?? '', $e->course->Title ?? '', $e->ProgressPercentage.'%', $e->PaymentStatus])->toArray()) !!},
    };
    const headers = {
        users: ['Name','Email','Role','Status'],
        courses: ['Title','Category','Price','Students'],
        enrolled: ['Student','Course','Progress','Payment'],
    };
    let csv = headers[type].join(',') + '\n';
    datasets[type].forEach(row => { csv += row.map(v => '"'+String(v).replace(/"/g,'""')+'"').join(',') + '\n'; });
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = type+'_report.csv'; a.click();
}
</script>
@endsection
