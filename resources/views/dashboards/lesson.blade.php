@extends('layouts.dashboard')

@section('title', $lesson->module->course->Title)

@section('content')
<div style="display: grid; grid-template-columns: 3fr 1fr; gap: 2rem;">
    {{-- Main Content Window --}}
    <div class="glass-card" style="padding: 0;">
        <div style="aspect-ratio: 16/9; background: #000; border-radius: 20px 20px 0 0; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative;">
            @if($lesson->ContentType == 'Video')
                <video controls style="width: 100%; height: 100%; object-fit: cover;">
                    <source src="{{ asset('storage/' . $lesson->URL) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @elseif($lesson->ContentType == 'PDF')
                <div style="text-align: center; color: white;">
                    <i class="fas fa-file-pdf" style="font-size: 5rem; color: #ef4444; margin-bottom: 1rem;"></i>
                    <p>Document: {{ basename($lesson->URL) }}</p>
                    <a href="{{ asset('storage/' . $lesson->URL) }}" target="_blank" class="btn btn-primary" style="margin-top: 1rem; text-decoration:none;"><i class="fas fa-download"></i> Download PDF</a>
                </div>
            @else
                <div style="text-align: center; color: white;">
                    <i class="fas fa-question-circle" style="font-size: 5rem; color: #fbbf24; margin-bottom: 1rem;"></i>
                    <p>Knowledge Assessment</p>
                    <button class="btn btn-primary" style="margin-top: 1rem;">Start Quiz</button>
                </div>
            @endif
        </div>
        
        <div style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2 style="font-size: 1.5rem;">{{ $lesson->module->ModuleTitle }}</h2>
                <div style="display: flex; gap: 0.5rem;">
                    @php
                        $allLessons = $lesson->module->lessons->sortBy('LessonID')->values();
                        $currentIndex = $allLessons->search(fn($l) => $l->LessonID == $lesson->LessonID);
                        $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
                        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;
                    @endphp
                    @if($prevLesson)
                        <a href="{{ route('student.lesson.view', $prevLesson->LessonID) }}" class="btn glass-card" style="padding: 0.5rem 1rem; text-decoration:none;"><i class="fas fa-chevron-left"></i> Previous</a>
                    @endif
                    @if($nextLesson)
                        <a href="{{ route('student.lesson.view', $nextLesson->LessonID) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; text-decoration:none;">Next Lesson <i class="fas fa-chevron-right"></i></a>
                    @endif
                </div>
            </div>
            <p style="color: var(--text-muted); line-height: 1.6;">
                In this lesson, we cover the core concepts of {{ $lesson->module->ModuleTitle }}. 
                Everything you need to master this topic is included in the {{ strtolower($lesson->ContentType) }} above.
            </p>

            @if($lesson->assessment)
                <div style="margin-top: 3rem; border-top: 1px solid var(--glass-border); padding-top: 2rem;">
                    <h3><i class="fas fa-file-alt" style="margin-right: 0.5rem; color: var(--accent);"></i> Assessment</h3>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                        Due: {{ \Carbon\Carbon::parse($lesson->assessment->DueDate)->format('M d, Y') }} | 
                        Total Score: {{ $lesson->assessment->TotalScore }}
                    </p>

                    @if($lesson->assessment->Instructions)
                    <div style="background: rgba(255,255,255,0.03); border-left: 4px solid var(--primary); padding: 1rem; border-radius: 0 12px 12px 0; margin-bottom: 1.5rem;">
                        <h4 style="margin-bottom: 0.5rem; color: white; font-size: 0.95rem;">Assignment Task:</h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5;">{{ $lesson->assessment->Instructions }}</p>
                    </div>
                    @endif
                    
                    @if($lesson->assessment->AttachmentURL)
                        <div style="margin-bottom: 1.5rem;">
                            <a href="{{ asset('storage/' . $lesson->assessment->AttachmentURL) }}" target="_blank" class="btn" style="background: rgba(99,102,241,0.1); color: var(--primary); border: 1px solid var(--primary); width: 100%; text-align: center; padding: 0.8rem; border-radius: 12px; text-decoration: none; display: block;">
                                <i class="fas fa-download" style="margin-right: 0.5rem;"></i>Download Task Document
                            </a>
                        </div>
                    @endif
                    
                    @if(session('status'))
                        <div style="background:rgba(16,185,129,0.15);border:1px solid #10b981;border-radius:12px;padding:1rem;margin-bottom:1rem;color:#10b981;">
                            <i class="fas fa-check-circle" style="margin-right:0.5rem;"></i>{{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('student.submission.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="AssessmentID" value="{{ $lesson->assessment->AssessmentID }}">
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display:block; color:var(--text-muted); font-size:0.85rem; margin-bottom:0.4rem;">Upload Your Work (PDF, Doc, Image, or Zip)</label>
                            <input type="file" name="SubmissionFile" required style="width:100%; background:var(--glass); border:1px solid var(--glass-border); border-radius:12px; color:white; padding:0.9rem;">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem;">
                            <i class="fas fa-cloud-upload-alt" style="margin-right:0.5rem;"></i>Upload & Submit Assessment
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- Course Sidebar --}}
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <div class="glass-card">
            <h4 style="margin-bottom: 1rem;">Course Progress</h4>
            @php
                $totalLessons = $lesson->module->lessons->count();
                $currentPos = $currentIndex + 1;
                $progress = $totalLessons > 0 ? round(($currentPos / $totalLessons) * 100) : 0;
            @endphp
            <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 10px; margin-bottom: 0.5rem;">
                <div style="width: {{ $progress }}%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 10px;"></div>
            </div>
            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $currentPos }} of {{ $totalLessons }} lessons</span>
        </div>

        <div class="glass-card" style="padding: 1.5rem;">
            <h4 style="margin-bottom: 1.5rem;">Lessons</h4>
            @foreach($lesson->module->lessons as $l)
                <a href="{{ route('student.lesson.view', $l->LessonID) }}" 
                   style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; border-radius: 12px; text-decoration: none; color: {{ $l->LessonID == $lesson->LessonID ? 'var(--primary)' : 'var(--text-muted)' }}; background: {{ $l->LessonID == $lesson->LessonID ? 'rgba(99,102,241,0.1)' : 'transparent' }}; margin-bottom: 0.5rem; transition: 0.3s;">
                    <i class="fas fa-{{ $l->ContentType == 'Video' ? 'play-circle' : ($l->ContentType == 'PDF' ? 'file-pdf' : 'question-circle') }}"></i>
                    <span style="font-size: 0.9rem;">Lesson {{ $loop->iteration }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
