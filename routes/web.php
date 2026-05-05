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

// Manual Migration Tool (Foolproof Fix)
Route::get('/force-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();
        
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();
        
        return "Migration and Seeding successful! <br><br><b>Migration Output:</b><pre>{$migrateOutput}</pre><br><b>Seeding Output:</b><pre>{$seedOutput}</pre>";
    } catch (\Exception $e) {
        return "Database build failed! Error: <br><pre>" . $e->getMessage() . "</pre>";
    }
});
