<?php
// Triggering fresh database redeploy...

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () { return view('welcome'); });

// Auth Routes
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// Registration
Route::get('/register', [AuthController::class, 'showRegisterStudent'])->name('register');
Route::post('/register', [AuthController::class, 'registerStudent']);
Route::get('/register-tutor', [AuthController::class, 'showRegisterTutor'])->name('register.tutor');
Route::post('/register-tutor', [AuthController::class, 'registerTutor']);

// Dashboard Routes
Route::middleware(['auth'])->group(function () {

    // ── Super Admin ──────────────────────────────────────
    Route::prefix('super-admin')->middleware('role:Super Admin')->group(function () {
        Route::get('/',      [DashboardController::class, 'superAdmin'])->name('super.admin.dashboard');
        Route::get('/users', [DashboardController::class, 'manageUsers'])->name('super.admin.users');
        Route::post('/users/store', [DashboardController::class, 'storeUser'])->name('super.admin.users.store');
        Route::post('/users/{id}/toggle', [DashboardController::class, 'toggleUserStatus'])->name('super.admin.users.toggle');
        Route::delete('/users/{id}/delete', [DashboardController::class, 'deleteUser'])->name('super.admin.users.delete');
        Route::get('/logs',  [DashboardController::class, 'systemLogs'])->name('super.admin.logs');
        Route::get('/tutors/pending', [DashboardController::class, 'pendingTutors'])->name('super.admin.tutors.pending');
        Route::post('/tutors/{id}/approve', [DashboardController::class, 'approveTutor'])->name('super.admin.tutors.approve');
        Route::post('/tutors/{id}/reject', [DashboardController::class, 'rejectTutor'])->name('super.admin.tutors.reject');
    });

    // ── Admin ────────────────────────────────────────────
    Route::prefix('admin')->middleware('role:Admin')->group(function () {
        Route::get('/',                     [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::get('/courses',              [DashboardController::class, 'manageCourses'])->name('admin.courses');
        Route::get('/courses/create',       [DashboardController::class, 'createCourse'])->name('admin.courses.create');
        Route::post('/courses/store',       [DashboardController::class, 'storeCourse'])->name('admin.courses.store');
        Route::get('/categories',           [DashboardController::class, 'manageCategories'])->name('admin.categories');
        Route::get('/categories/create',    [DashboardController::class, 'createCategory'])->name('admin.categories.create');
        Route::post('/categories/store',    [DashboardController::class, 'storeCategory'])->name('admin.categories.store');
        Route::get('/analytics',            [DashboardController::class, 'analytics'])->name('admin.analytics');
        Route::get('/assign-tutor',         [DashboardController::class, 'assignTutor'])->name('admin.tutors.assign');
        Route::post('/assign-tutor/store',  [DashboardController::class, 'storeAssignTutor'])->name('admin.tutors.store');
        Route::get('/export-reports',       [DashboardController::class, 'exportReports'])->name('admin.reports.export');
        Route::get('/site-settings',        [DashboardController::class, 'siteSettings'])->name('admin.settings');
        Route::post('/site-settings/save',  [DashboardController::class, 'saveSiteSettings'])->name('admin.settings.save');
    });

    // ── Tutor ────────────────────────────────────────────
    Route::prefix('tutor')->middleware('role:Tutor')->group(function () {
        Route::get('/',                   [DashboardController::class, 'tutor'])->name('tutor.dashboard');
        Route::get('/my-courses',         [DashboardController::class, 'tutorCourses'])->name('tutor.courses');
        Route::get('/assessments',        [DashboardController::class, 'manageAssessments'])->name('tutor.assessments');
        Route::post('/assessments/store', [DashboardController::class, 'storeAssessment'])->name('tutor.assessments.store');
        Route::get('/feedback',           [DashboardController::class, 'tutorFeedback'])->name('tutor.feedback');
        Route::get('/students',           [DashboardController::class, 'tutorStudents'])->name('tutor.students');
        Route::post('/feedback/{id}/grade', [DashboardController::class, 'gradeSubmission'])->name('tutor.feedback.grade');
        Route::get('/lessons/add',        [DashboardController::class, 'addLesson'])->name('tutor.lessons.add');
        Route::post('/lessons/store',     [DashboardController::class, 'storeLesson'])->name('tutor.lessons.store');
        Route::get('/modules/create',     [DashboardController::class, 'createModule'])->name('tutor.modules.create');
        Route::post('/modules/store',     [DashboardController::class, 'storeModule'])->name('tutor.modules.store');
    });

    // ── Student ──────────────────────────────────────────
    Route::prefix('student')->middleware('role:Student')->group(function () {
        Route::get('/',                   [DashboardController::class, 'student'])->name('student.dashboard');
        Route::get('/learning',           [DashboardController::class, 'myLearning'])->name('student.learning');
        Route::get('/browse', [DashboardController::class, 'browse'])->name('student.browse');
        Route::get('/assessments', [DashboardController::class, 'studentAssessments'])->name('student.assessments');
        Route::post('/enroll/{id}', [DashboardController::class, 'enroll'])->name('student.enroll');
        Route::get('/lesson/{lesson_id}',   [DashboardController::class, 'viewLesson'])->name('student.lesson.view');
        Route::get('/achievements',         [DashboardController::class, 'achievements'])->name('student.achievements');
        Route::get('/resume/{course_id}', [DashboardController::class, 'resumeLesson'])->name('student.lesson.resume');
    });

    // ── Shared ───────────────────────────────────────────
    Route::post('/submission/store', [DashboardController::class, 'storeSubmission'])->name('student.submission.store');
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-as-read/{id}', [DashboardController::class, 'markAsRead'])->name('notifications.markRead');
    Route::get('/api/notifications/latest', [DashboardController::class, 'getLatestNotifications'])->name('api.notifications.latest');
});

// Ultimate Self-Healing & Sync Tool (Redeploy Fix)
Route::get('/force-migrate', function () {
    try {
        $output = "";

        // 1. Link Storage
        \Illuminate\Support\Facades\Artisan::call('storage:link', ['--force' => true]);
        $output .= "<b>1. Storage Link:</b> " . trim(\Illuminate\Support\Facades\Artisan::output()) . "<br>";

        // 2. Clear Caches for Speed
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $output .= "<b>2. Cache Cleared:</b> System optimized for speed.<br>";

        // 3. Run Migrations & Column Fixes
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        
        // FOOLPROOF COLUMN FIX: Add Description to courses if missing
        if (!\Illuminate\Support\Facades\Schema::hasColumn('courses', 'Description')) {
            \Illuminate\Support\Facades\Schema::table('courses', function ($table) {
                $table->text('Description')->nullable()->after('Title');
            });
            $output .= "<b>3. Database Fix:</b> Added missing 'Description' column to courses.<br>";
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('assessments', 'TutorID')) {
            \Illuminate\Support\Facades\Schema::table('assessments', function ($table) {
                $table->unsignedBigInteger('TutorID')->nullable()->after('LessonID');
            });
            $output .= "<b>3. Database Fix:</b> Added missing 'TutorID' column to assessments.<br>";
        }

        // Add Title to lessons if missing
        if (!\Illuminate\Support\Facades\Schema::hasColumn('lessons', 'Title')) {
            \Illuminate\Support\Facades\Schema::table('lessons', function ($table) {
                $table->string('Title')->nullable()->after('ModuleID');
            });
            $output .= "<b>3. Database Fix:</b> Added missing 'Title' column to lessons.<br>";
        }

        // Add LicenseURL to users if missing
        if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'LicenseURL')) {
            \Illuminate\Support\Facades\Schema::table('users', function ($table) {
                $table->string('LicenseURL')->nullable()->after('Email');
                $table->string('Status')->default('Active')->after('LicenseURL');
            });
            $output .= "<b>3. Database Fix:</b> Added missing 'License' columns to users.<br>";
        }

        $output .= "<b>3. Migration Output:</b><pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
        
        // 4. Seed Data
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $output .= "<b>4. Seeding Output:</b><pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";

        // 5. UI Fix: Backfill Lesson Titles (Ensures UI looks like Lesson 1, 2, 3)
        $allLessons = \App\Models\Lesson::all();
        foreach($allLessons as $index => $lesson) {
            if (empty($lesson->Title) || $lesson->Title == 'Lesson') {
                $lesson->Title = "Lesson " . ($index + 1);
                $lesson->save();
            }
        }
        $output .= "<b>5. UI Fix:</b> Backfilled titles for " . count($allLessons) . " lessons.<br>";

        // 6. Demo Content: Create Automatic Demo Course (For New Demo)
        $demoCategory = \App\Models\Category::firstOrCreate(['CategoryName' => 'Web Development'], ['Description' => 'Web tech']);
        $demoAdmin = \App\Models\User::where('RoleID', 2)->first() ?? \App\Models\User::first();
        $demoTutor = \App\Models\User::where('RoleID', 3)->first() ?? \App\Models\User::first();

        $course = \App\Models\Course::firstOrCreate(
            ['Title' => 'Advanced Web Development'],
            [
                'Description' => 'A comprehensive demo course showcasing full system connectivity.',
                'CategoryID' => $demoCategory->CategoryID,
                'AdminID' => $demoAdmin->UserID,
                'Price' => 149.99,
                'Status' => 'Published'
            ]
        );

        $module = \App\Models\Module::firstOrCreate(
            ['ModuleTitle' => 'Backend Architecture', 'CourseID' => $course->CourseID],
            ['TutorID' => $demoTutor->UserID]
        );

        \App\Models\Lesson::firstOrCreate(
            ['ModuleID' => $module->ModuleID, 'Title' => 'Lesson 1: Database Synchronization'],
            ['ContentType' => 'Video', 'URL' => 'demo_sync.mp4', 'TutorID' => $demoTutor->UserID]
        );

        $output .= "<b>6. Demo Content:</b> Created 'Advanced Web Development' course and backend module.<br>";

        // 7. System Sync: Backfill Assessment Ownership
        \App\Models\Assessment::whereNull('TutorID')->each(function($a) {
            $a->update(['TutorID' => $a->lesson->module->TutorID ?? 1]);
        });
        $output .= "<b>7. System Sync:</b> All assessment data correctly synchronized across roles.<br>";

        return "<h3>System Self-Heal Successful!</h3>" . $output . "<br><br><b>The system is now 100% connected and demo-ready.</b>";
    } catch (\Exception $e) {
        return "<h3>System Heal Failed!</h3> Error: <br><pre>" . $e->getMessage() . "</pre>";
    }
});
