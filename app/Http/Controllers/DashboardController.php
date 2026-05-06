<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\SystemLog;
use App\Models\Category;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Assessment;
use App\Models\Notification;
use App\Models\Submission;
use App\Models\Role;
use App\Models\Subject;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    // ──────────────────────────────────────────────────────
    // OVERVIEW DASHBOARDS
    // ──────────────────────────────────────────────────────

    public function superAdmin()
    {
        $stats = [
            'users'   => User::count(),
            'admins'  => User::whereHas('role', fn($q) => $q->where('RoleName', 'Admin'))->count(),
            'tutors'  => User::whereHas('role', fn($q) => $q->where('RoleName', 'Tutor'))->count(),
            'students'=> User::whereHas('role', fn($q) => $q->where('RoleName', 'Student'))->count(),
            'logs'    => SystemLog::latest()->take(10)->get(),
        ];
        return view('dashboards.super-admin', compact('stats'));
    }

    public function admin()
    {
        $stats = [
            'courses'        => Course::where('AdminID', auth()->user()->UserID)->count(),
            'students'       => Enrollment::count(),
            'categories'     => Category::count(),
            'revenue'        => Course::where('AdminID', auth()->user()->UserID)->sum('Price'),
            'recent_courses' => Course::where('AdminID', auth()->user()->UserID)->with('category')->latest()->take(5)->get(),
        ];
        return view('dashboards.admin', compact('stats'));
    }

    public function tutor()
    {
        $user = auth()->user();
        $stats = [
            'modules'          => Module::where('TutorID', $user->UserID)->count(),
            'courses_teaching' => Course::whereHas('modules', fn($q) => $q->where('TutorID', $user->UserID))->count(),
            'assessments'      => Assessment::where('TutorID', $user->UserID)->count(),
        ];
        return view('dashboards.tutor', compact('stats'));
    }

    public function student()
    {
        $user = auth()->user();
        $enrollments = $user->enrollment()->with(['course.category', 'course.modules.lessons'])->latest()->get();
        
        $stats = [
            'enrolled'      => $enrollments->count(),
            'completed'     => $enrollments->where('ProgressPercentage', 100)->count(),
            'avg_progress'  => round($enrollments->avg('ProgressPercentage') ?? 0),
            'notifications' => Notification::where('UserID', $user->UserID)->where('IsRead', false)->count(),
        ];
        
        return view('dashboards.student', compact('enrollments', 'stats'));
    }

    // ──────────────────────────────────────────────────────
    // SUPER ADMIN FEATURES
    // ──────────────────────────────────────────────────────

    public function manageUsers()
    {
        $users = User::with('role')->latest()->paginate(15);
        $roles = Role::all();
        return view('dashboards.users', compact('users', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'Name'     => 'required|string|max:255',
            'Email'    => 'required|email|unique:users,Email',
            'Password' => 'required|string|min:6',
            'RoleID'   => 'required|integer|exists:roles,RoleID',
        ]);

        User::create([
            'Name'     => $request->Name,
            'Email'    => $request->Email,
            'Password' => Hash::make($request->Password),
            'RoleID'   => $request->RoleID,
            'Status'   => 'Active',
        ]);

        SystemLog::record('Created user: ' . $request->Email, $request);

        return redirect()->route('super.admin.users')->with('status', 'User created successfully!');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->Status = $user->Status === 'Active' ? 'Inactive' : 'Active';
        $user->save();

        SystemLog::record('Toggled status of ' . $user->Email . ' to ' . $user->Status);

        return redirect()->route('super.admin.users')->with('status', $user->Name . ' is now ' . $user->Status);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $email = $user->Email;

        // Prevent self-deletion
        if ($user->UserID === auth()->user()->UserID) {
            return redirect()->route('super.admin.users')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        SystemLog::record('Deleted user: ' . $email);

        return redirect()->route('super.admin.users')->with('status', 'User deleted successfully.');
    }

    public function systemLogs()
    {
        $logs = SystemLog::with('user')->latest()->paginate(20);
        return view('dashboards.system-logs', compact('logs'));
    }

    public function pendingTutors()
    {
        $tutors = User::where('RoleID', 3)->where('Status', 'Pending')->get();
        return view('dashboards.pending-tutors', compact('tutors'));
    }

    public function approveTutor($id)
    {
        $user = User::findOrFail($id);
        $user->Status = 'Active';
        $user->save();

        SystemLog::record('Approved tutor: ' . $user->Email);

        return redirect()->back()->with('status', 'Tutor account approved and activated!');
    }

    public function rejectTutor($id)
    {
        $user = User::findOrFail($id);
        $email = $user->Email;
        $user->delete();

        SystemLog::record('Rejected/Deleted pending tutor: ' . $email);

        return redirect()->back()->with('status', 'Tutor application rejected.');
    }

    // ──────────────────────────────────────────────────────
    // ADMIN FEATURES
    // ──────────────────────────────────────────────────────

    public function manageCourses()
    {
        $courses = Course::with(['category', 'admin'])->withCount('enrollments')->latest()->paginate(10);
        return view('dashboards.courses', compact('courses'));
    }

    public function createCourse()
    {
        $categories = Category::all();
        $admins = User::whereHas('role', fn($q) => $q->where('RoleName', 'Admin'))->get();
        return view('dashboards.create-course', compact('categories', 'admins'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'Title'       => 'required|string|max:255',
            'CategoryID'  => 'required|integer|exists:categories,CategoryID',
            'Description' => 'nullable|string|max:1000',
            'Price'       => 'required|numeric|min:0',
            'AdminID'     => 'required|integer|exists:users,UserID',
        ]);

        Course::create([
            'Title'       => $request->Title,
            'Description' => $request->Description,
            'CategoryID'  => $request->CategoryID,
            'AdminID'     => $request->AdminID,
            'Price'       => $request->Price,
        ]);

        SystemLog::record('Created course: ' . $request->Title, $request);

        return redirect()->route('admin.courses')->with('status', 'Course created successfully!');
    }

    public function manageCategories()
    {
        $categories = Category::withCount('courses')->latest()->paginate(15);
        return view('dashboards.categories', compact('categories'));
    }

    public function createCategory()
    {
        return view('dashboards.create-category');
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['CategoryName' => 'required|string|max:255|unique:categories,CategoryName']);
        Category::create(['CategoryName' => $request->CategoryName]);
        SystemLog::record('Category created: ' . $request->CategoryName, $request);
        return redirect()->route('admin.categories')->with('status', 'Category created successfully!');
    }

    public function analytics()
    {
        $data = [
            'total_users'    => User::count(),
            'total_courses'  => Course::count(),
            'total_enrolled' => Enrollment::count(),
            'total_revenue'  => Course::sum('Price'),
            'by_role'        => [
                'admins'   => User::whereHas('role', fn($q) => $q->where('RoleName', 'Admin'))->count(),
                'tutors'   => User::whereHas('role', fn($q) => $q->where('RoleName', 'Tutor'))->count(),
                'students' => User::whereHas('role', fn($q) => $q->where('RoleName', 'Student'))->count(),
            ],
            'top_courses'    => Course::withCount('enrollments')->orderBy('enrollments_count', 'desc')->take(5)->get(),
            'categories'     => Category::withCount('courses')->get(),
        ];
        return view('dashboards.analytics', compact('data'));
    }

    public function assignTutor()
    {
        $tutors  = User::whereHas('role', fn($q) => $q->where('RoleName', 'Tutor'))->get();
        $modules = Module::with(['course', 'tutor'])->latest()->paginate(15);
        return view('dashboards.assign-tutor', compact('tutors', 'modules'));
    }

    public function storeAssignTutor(Request $request)
    {
        $request->validate([
            'ModuleID' => 'required|integer|exists:modules,ModuleID',
            'TutorID'  => 'required|integer|exists:users,UserID',
        ]);
        Module::findOrFail($request->ModuleID)->update(['TutorID' => $request->TutorID]);
        SystemLog::record('Assigned tutor to module #' . $request->ModuleID, $request);
        return redirect()->route('admin.tutors.assign')->with('status', 'Tutor assigned successfully!');
    }

    public function exportReports()
    {
        $data = [
            'users'    => User::with('role')->get(),
            'courses'  => Course::with(['category', 'admin'])->withCount('enrollments')->get(),
            'enrolled' => Enrollment::with(['student', 'course'])->get(),
        ];
        return view('dashboards.export-reports', compact('data'));
    }

    public function siteSettings()
    {
        return view('dashboards.site-settings');
    }

    public function saveSiteSettings(Request $request)
    {
        SystemLog::record('Site settings updated', $request);
        return redirect()->route('admin.settings')->with('status', 'Settings saved successfully!');
    }

    // ──────────────────────────────────────────────────────
    // TUTOR FEATURES
    // ──────────────────────────────────────────────────────

    public function tutorCourses()
    {
        $user = auth()->user();
        $courses = Course::whereHas('modules', fn($q) => $q->where('TutorID', $user->UserID))
            ->with(['category', 'modules.lessons'])->withCount('modules')->latest()->paginate(10);
        return view('dashboards.tutor-courses', compact('courses'));
    }

    public function manageAssessments()
    {
        $user = auth()->user();
        
        // Direct query by TutorID is now indexed and ultra-fast
        $assessments = Assessment::where('TutorID', $user->UserID)
            ->with(['lesson.module.course', 'submissions.student'])
            ->withCount('submissions')
            ->latest()
            ->paginate(15);
        
        $lessons = Lesson::where('TutorID', $user->UserID)
            ->with('module.course')
            ->orderBy('created_at', 'asc')
            ->get();
        
        return view('dashboards.assessments', compact('assessments', 'lessons'));
    }

    public function storeAssessment(Request $request)
    {
        $request->validate([
            'LessonID'       => 'required|integer|exists:lessons,LessonID',
            'DueDate'        => 'required|date',
            'TotalScore'     => 'required|integer|min:1',
            'Instructions'   => 'nullable|string',
            'AttachmentFile' => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,png|max:5120',
        ]);

        $data = $request->only(['LessonID', 'DueDate', 'TotalScore', 'Instructions']);
        $data['TutorID'] = auth()->user()->UserID; // Direct ownership link

        if ($request->hasFile('AttachmentFile')) {
            $path = $request->file('AttachmentFile')->store('attachments', 'public');
            $data['AttachmentURL'] = $path;
        }

        Assessment::create($data);
        
        SystemLog::record('Created assessment task for Lesson #' . $request->LessonID, $request);
        
        return redirect()->route('tutor.assessments')->with('status', 'Assessment created successfully!');
    }

    public function tutorFeedback()
    {
        $user = auth()->user();
        $submissions = Submission::whereHas('assessment.lesson.module', fn($q) => $q->where('TutorID', $user->UserID))
            ->with(['student', 'assessment.lesson.module.course'])->latest()->paginate(15);
        return view('dashboards.feedback', compact('submissions'));
    }

    public function tutorStudents()
    {
        $user = auth()->user();
        $courseIds = Course::whereHas('modules', fn($q) => $q->where('TutorID', $user->UserID))->pluck('CourseID');
        
        $enrollments = Enrollment::whereIn('CourseID', $courseIds)
            ->with(['student', 'course'])
            ->latest()
            ->paginate(20);
            
        return view('dashboards.tutor_students', compact('enrollments'));
    }

    public function gradeSubmission(Request $request, $id)
    {
        $request->validate([
            'Grade'    => 'required|integer|min:0',
            'Feedback' => 'nullable|string|max:1000',
        ]);

        $submission = Submission::findOrFail($id);
        $submission->update([
            'Grade'    => $request->Grade,
            'Feedback' => $request->Feedback,
        ]);

        // Notify student
        Notification::create([
            'UserID'  => $submission->StudentID,
            'Message' => 'Your submission has been graded: ' . $request->Grade . ' pts',
            'IsRead'  => false,
        ]);

        return redirect()->route('tutor.feedback')->with('status', 'Submission graded successfully!');
    }

    public function addLesson()
    {
        $user = auth()->user();
        $modules = Module::where('TutorID', $user->UserID)->with('course')->get();
        return view('dashboards.add-lesson', compact('modules'));
    }

    public function storeLesson(Request $request)
    {
        $request->validate([
            'ModuleID'    => 'required|integer|exists:modules,ModuleID',
            'Title'       => 'required|string|max:255',
            'ContentType' => 'required|string',
            'LessonFile'  => 'required|file|mimes:mp4,mov,avi,pdf|max:102400', // 100MB max
        ]);

        $path = $request->file('LessonFile')->store('lessons', 'public');

        Lesson::create([
            'ModuleID'    => $request->ModuleID,
            'Title'       => $request->Title,
            'ContentType' => $request->ContentType,
            'URL'         => $path,
            'TutorID'     => auth()->user()->UserID,
        ]);
        return redirect()->route('tutor.courses')->with('status', 'Lesson created and content uploaded successfully!');
    }

    public function createModule()
    {
        $user = auth()->user();
        // Show all courses (admin-managed or ones tutor already teaches)
        $courses = Course::all();
        return view('dashboards.create-module', compact('courses'));
    }

    public function storeModule(Request $request)
    {
        $request->validate([
            'CourseID'    => 'required|integer|exists:courses,CourseID',
            'ModuleTitle' => 'required|string|max:255',
        ]);

        Module::create([
            'CourseID'    => $request->CourseID,
            'ModuleTitle' => $request->ModuleTitle,
            'TutorID'     => auth()->user()->UserID,
        ]);

        SystemLog::record('Created module: ' . $request->ModuleTitle);

        return redirect()->route('tutor.courses')->with('status', 'Module created successfully!');
    }

    // ──────────────────────────────────────────────────────
    // STUDENT FEATURES
    // ──────────────────────────────────────────────────────

    public function myLearning()
    {
        $enrollments = auth()->user()->enrollment()->with(['course.category', 'course.admin', 'course.modules.lessons'])->latest()->paginate(6);
        return view('dashboards.my-learning', compact('enrollments'));
    }

    public function browse()
    {
        $enrolled = auth()->user()->enrollment()->pluck('CourseID')->toArray();
        $courses = Course::with(['category', 'admin'])->latest()->paginate(12);
        return view('dashboards.browse', compact('courses', 'enrolled'));
    }

    public function studentAssessments()
    {
        $user = auth()->user();
        $submissions = Submission::with(['assessment.lesson.module.course'])
            ->where('StudentID', $user->UserID)
            ->get();

        $enrolledCourseIDs = Enrollment::where('StudentID', $user->UserID)->pluck('CourseID')->toArray();
        
        $pendingAssessments = Assessment::join('lessons', 'assessments.LessonID', '=', 'lessons.LessonID')
            ->join('modules', 'lessons.ModuleID', '=', 'modules.ModuleID')
            ->whereIn('modules.CourseID', $enrolledCourseIDs)
            ->whereDoesntHave('submissions', function($q) use ($user) {
                $q->where('StudentID', $user->UserID);
            })
            ->select('assessments.*')
            ->with(['lesson.module.course'])
            ->get();

        return view('dashboards.student_assessments', compact('submissions', 'pendingAssessments'));
    }

    public function enroll($course_id)
    {
        $user = auth()->user();
        Enrollment::firstOrCreate([
            'StudentID' => $user->UserID,
            'CourseID' => $course_id,
            'ProgressPercentage' => 0,
            'PaymentStatus' => 'Paid'
        ]);

        Notification::create([
            'UserID' => $user->UserID,
            'Message' => 'You have successfully joined the course!',
            'IsRead' => false
        ]);

        return redirect()->back()->with('status', 'Welcome! You have successfully joined the course.');
    }

    public function achievements()
    {
        $user = auth()->user();
        $enrollments = $user->enrollment()->with('course')->get();
        $completed   = $enrollments->where('ProgressPercentage', 100)->count();
        $total       = $enrollments->count();
        return view('dashboards.achievements', compact('enrollments', 'completed', 'total'));
    }

    public function resumeLesson($course_id)
    {
        // Find the first lesson of the course for the student
        $course = Course::with('modules.lessons')->findOrFail($course_id);
        $firstLesson = null;
        foreach ($course->modules as $module) {
            if ($module->lessons->count() > 0) {
                $firstLesson = $module->lessons->first();
                break;
            }
        }

        if ($firstLesson) {
            return redirect()->route('student.lesson.view', $firstLesson->LessonID);
        }

        return redirect()->route('student.learning')->with('status', 'No lessons available yet for this course.');
    }

    // ──────────────────────────────────────────────────────
    // SHARED FEATURES
    // ──────────────────────────────────────────────────────


    public function viewLesson($lesson_id)
    {
        $lesson = Lesson::with(['module.course', 'module.lessons', 'assessment'])->findOrFail($lesson_id);
        
        $isEnrolled = Enrollment::where('StudentID', auth()->user()->UserID)
                                ->where('CourseID', $lesson->module->CourseID)
                                ->exists();
                                
        if (!$isEnrolled) {
            return redirect()->route('student.browse')->with('status', 'Please enroll in the course first.');
        }

        return view('dashboards.lesson', compact('lesson'));
    }

    public function storeSubmission(Request $request)
    {
        $request->validate([
            'AssessmentID'   => 'required|integer|exists:assessments,AssessmentID',
            'SubmissionFile' => 'required|file|mimes:pdf,doc,docx,zip,jpg,png|max:10240',
        ]);

        $path = $request->file('SubmissionFile')->store('submissions', 'public');

        // Get first subject or use default
        $subject = Subject::first();

        Submission::create([
            'AssessmentID' => $request->AssessmentID,
            'StudentID'    => auth()->user()->UserID,
            'SubjectID'    => $subject ? $subject->SubjectID : 1,
            'FileURL'      => $path,
            'Grade'        => null,
            'Feedback'     => null,
        ]);

        // Notify Tutor
        $assessment = Assessment::with('lesson.module.course')->find($request->AssessmentID);
        if ($assessment && $assessment->lesson && $assessment->lesson->module) {
            Notification::create([
                'UserID' => $assessment->lesson->module->TutorID,
                'Message' => auth()->user()->Name . ' submitted work for "' . ($assessment->lesson->module->course->Title ?? 'a course') . '"',
                'IsRead' => false
            ]);
        }

        return redirect()->back()->with('status', 'Assessment submitted successfully!');
    }

    public function manageStudents()
    {
        $user = auth()->user();
        $students = User::whereHas('enrollment.course.modules', fn($q) => $q->where('TutorID', $user->UserID))
            ->with('enrollment.course')
            ->distinct()
            ->paginate(15);
        return view('dashboards.tutor-students', compact('students'));
    }

    public function notifications()
    {
        $notifications = Notification::where('UserID', auth()->user()->UserID)
            ->latest()
            ->paginate(20);
        return view('dashboards.notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        Notification::where('UserID', auth()->user()->UserID)->where('NotifyID', $id)->update(['IsRead' => true]);
        return response()->json(['success' => true]);
    }

    public function getLatestNotifications()
    {
        $notifications = Notification::where('UserID', auth()->user()->UserID)
            ->where('IsRead', false)
            ->latest()
            ->get();
        return response()->json($notifications);
    }
}
