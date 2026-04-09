<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProgressController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Certificate verification (public)
Route::get('/certificates/verify/{code}', [CertificateController::class, 'verify'])->name('certificates.verify');

// Course catalog (public view)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show')
    ->where('course', '[0-9]+');

// Search (public)
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Lesson view (public)
Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show')
    ->where('lesson', '[0-9]+');

// ==================== AUTHENTICATED ROUTES ====================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // My Courses (Kursus Saya) - untuk enrolled courses
    Route::get('/courses/my', [CourseController::class, 'myCourses'])->name('courses.my');

    // Progress (Kursus Saya) - untuk progress of enrolled courses
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    // Search
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Certificates (authenticated users)
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/{certificate}/preview', [CertificateController::class, 'preview'])->name('certificates.preview');

    // Course Enrollment
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('courses.enroll');
});

// ==================== SISWA ROUTES ====================
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {

    // Enrollment
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('enroll');
    Route::post('/lessons/{lesson}/complete', [EnrollmentController::class, 'completeLesson'])->name('lessons.complete');
    Route::get('/courses/{course}/progress', [EnrollmentController::class, 'progress'])->name('courses.progress');

    // Quiz attempts
    Route::get('/quizzes/{quiz}/attempt', [QuizController::class, 'attempt'])->name('quizzes.attempt');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/{attempt}/result', [QuizController::class, 'result'])->name('quizzes.result');
    // My Courses (Kursus Saya)
    Route::get('/courses/my', [CourseController::class, 'myCourses'])->name('courses.my');

    });

// ==================== GURU & ADMIN ROUTES ====================
Route::middleware(['auth', 'role:guru,admin'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Course Management (without prefix for better route naming)
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{course}/duplicate', [CourseController::class, 'duplicate'])->name('courses.duplicate');
    Route::post('/courses/{course}/publish', [CourseController::class, 'publish'])->name('courses.publish');
    Route::post('/courses/{course}/unpublish', [CourseController::class, 'unpublish'])->name('courses.unpublish');

    // Lesson Management
    Route::get('/courses/{course}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
    Route::post('/courses/{course}/lessons/reorder', [LessonController::class, 'reorder'])->name('lessons.reorder');
    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');

    // Quiz Management
    Route::get('/courses/{course}/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/courses/{course}/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');

    // Question Management
    Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Grading
    Route::get('/grading', [GradingController::class, 'index'])->name('grading.index');
    Route::get('/grading/{attempt}', [GradingController::class, 'show'])->name('grading.show');
    Route::post('/grading/{attempt}', [GradingController::class, 'grade'])->name('grading.grade');
    Route::post('/grading/{attempt}/bulk', [GradingController::class, 'bulkGrade'])->name('grading.bulk');
    Route::post('/grading/{attempt}/reset', [GradingController::class, 'resetAttempt'])->name('grading.reset');

    // Student Management (for teachers)
    Route::get('/courses/{course}/students', [EnrollmentController::class, 'students'])->name('courses.students');
    Route::get('/courses/{course}/students/export', [EnrollmentController::class, 'exportStudents'])->name('courses.students.export');
});

// ==================== ADMIN ONLY ROUTES ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('users.toggle-role');
    Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');

    // Category Management
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/courses', [ReportController::class, 'courses'])->name('reports.courses');
    Route::get('/reports/users', [ReportController::class, 'users'])->name('reports.users');
    Route::get('/reports/enrollments', [ReportController::class, 'enrollments'])->name('reports.enrollments');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // System Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{log}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::post('/activity-logs', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');

    // Backup
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download/{file}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/delete/{file}', [BackupController::class, 'delete'])->name('backup.delete');
});

// ==================== API-LIKE ROUTES (AJAX) ====================
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {

    // Course search (ajax)
    Route::get('/courses/search', [CourseController::class, 'search'])->name('courses.search');

    // Progress tracking (ajax)
    Route::get('/courses/{course}/progress', [EnrollmentController::class, 'getProgress'])->name('progress.get');

    // Notifications (ajax)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// ==================== ADDITIONAL ROUTES ====================

// Route untuk melihat lesson tanpa harus login (jika public)
Route::get('/lessons/{lesson}/preview', [LessonController::class, 'preview'])->name('lessons.preview')->middleware('auth');

// Route untuk download materi (dengan validasi enrollment)
Route::get('/lessons/{lesson}/download-pdf', [LessonController::class, 'downloadPdf'])->name('lessons.download-pdf')->middleware('auth');

// Route untuk quiz review
Route::get('/quizzes/{quiz}/review', [QuizController::class, 'review'])->name('quizzes.review')->middleware('auth');

// Route untuk leaderboard kursus
Route::get('/courses/{course}/leaderboard', [CourseController::class, 'leaderboard'])->name('courses.leaderboard');

// Route untuk forum diskusi (optional)
Route::prefix('forum')->name('forum.')->middleware(['auth'])->group(function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('/discussions/create', [ForumController::class, 'create'])->name('discussions.create');
    Route::post('/discussions', [ForumController::class, 'store'])->name('discussions.store');
    Route::get('/discussions/{discussion}', [ForumController::class, 'show'])->name('discussions.show');
    Route::post('/discussions/{discussion}/replies', [ForumController::class, 'reply'])->name('replies.store');
});

// Include auth routes from Breeze
require __DIR__.'/auth.php';
