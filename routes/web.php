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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ProgressController;
use Illuminate\Support\Facades\Route;

// ============================================================================
// ADMIN CONTROLLERS
// ============================================================================
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;

// ============================================================================
// GURU CONTROLLERS
// ============================================================================
use App\Http\Controllers\Guru\StudentController as GuruStudentController;
use App\Http\Controllers\Guru\CourseController as GuruCourseController;
use App\Models\Lesson;

// ============================================================================
// ROUTE MODEL BINDING
// ============================================================================
Route::model('lesson', Lesson::class);

// ============================================================================
// PUBLIC ROUTES (Tidak memerlukan authentication)
// ============================================================================

// Homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Certificate verification (public)
Route::get('/certificates/verify/{code}', [CertificateController::class, 'verify'])->name('certificates.verify');

// Course catalog (public view)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

// Public search
Route::get('/search', [SearchController::class, 'search'])->name('search');

// ============================================================================
// GURU & ADMIN ROUTES - MUST BE BEFORE PUBLIC SHOW ROUTE (Harus sebelum rute publik)
// ============================================================================
Route::middleware(['auth', 'role:guru,admin'])->group(function () {

    // ==================== COURSE MANAGEMENT ====================
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{course}/duplicate', [CourseController::class, 'duplicate'])->name('courses.duplicate');
    Route::post('/courses/{course}/publish', [CourseController::class, 'publish'])->name('courses.publish');
    Route::post('/courses/{course}/unpublish', [CourseController::class, 'unpublish'])->name('courses.unpublish');

    // ==================== LESSON MANAGEMENT ====================
    Route::get('/courses/{course}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::get('/courses/{course}/lessons/{lesson}/edit', [LessonController::class, 'edit'])->where('lesson', '[0-9]+')->name('lessons.edit');
    Route::put('/courses/{course}/lessons/{lesson}', [LessonController::class, 'update'])->where('lesson', '[0-9]+')->name('lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}', [LessonController::class, 'destroy'])->where('lesson', '[0-9]+')->name('lessons.destroy');
    Route::post('/courses/{course}/lessons/reorder', [LessonController::class, 'reorder'])->name('lessons.reorder');
    
    // Guru dapat menandai lesson siswa sebagai selesai
    Route::post('/courses/{course}/lessons/{lesson}/mark-complete/{user}', [LessonController::class, 'markComplete'])->where('lesson', '[0-9]+')->name('lessons.mark-complete');

    // ==================== QUIZ MANAGEMENT ====================
    Route::get('/courses/{course}/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/courses/{course}/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');

    // ==================== QUESTION MANAGEMENT ====================
    Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // ==================== GRADING ====================
    Route::get('/grading', [GradingController::class, 'index'])->name('grading.index');
    Route::get('/grading/{attempt}', [GradingController::class, 'show'])->name('grading.show');
    Route::post('/grading/{attempt}', [GradingController::class, 'grade'])->name('grading.grade');
    Route::post('/grading/{attempt}/bulk', [GradingController::class, 'bulkGrade'])->name('grading.bulk');
    Route::post('/grading/{attempt}/reset', [GradingController::class, 'resetAttempt'])->name('grading.reset');

    // ==================== STUDENT MANAGEMENT (untuk guru & admin) ====================
    Route::get('/courses/{course}/students', [EnrollmentController::class, 'students'])->name('courses.students');
    Route::get('/courses/{course}/students/export', [EnrollmentController::class, 'exportStudents'])->name('courses.students.export');
});

// Course detail (public - harus setelah routes yang lebih spesifik)
Route::get('/courses/{course}', [CourseController::class, 'show'])->where('course', '(?!create|edit|delete).+')->name('courses.show');

// ============================================================================
// AUTHENTICATED ROUTES (Semua user yang login)
// ============================================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Progress
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Certificates
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/{certificate}/preview', [CertificateController::class, 'preview'])->name('certificates.preview');

    // Course Enrollment (Siswa)
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('courses.enroll');

    // ==================== LESSON ROUTES ====================
    // Lesson view untuk semua role
    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');

    // Complete lesson
    Route::post('/courses/{course}/lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');

    // Update video progress
    Route::post('/courses/{course}/lessons/{lesson}/progress', [LessonController::class, 'updateProgress'])->name('lessons.progress');

    // Preview lesson
    Route::get('/lessons/{lesson}/preview', [LessonController::class, 'preview'])->name('lessons.preview');

    // Download PDF
    Route::get('/courses/{course}/lessons/{lesson}/download', [LessonController::class, 'downloadPdf'])->name('lessons.download');

    // Quiz review
    Route::get('/quizzes/{quiz}/review', [QuizController::class, 'review'])->name('quizzes.review');

    // Leaderboard kursus
    Route::get('/courses/{course}/leaderboard', [CourseController::class, 'leaderboard'])->name('courses.leaderboard');
});

// ============================================================================
// SISWA ROUTES (Hanya role siswa)
// ============================================================================
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {

    // My Courses (Kursus yang diambil siswa)
    Route::get('/courses', [CourseController::class, 'myCourses'])->name('courses.my');

    // Enrollment
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('enroll');

    // Lesson routes untuk siswa
    Route::post('/courses/{course}/lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');
    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');

    // Progress
    Route::get('/courses/{course}/progress', [EnrollmentController::class, 'progress'])->name('courses.progress');

    // Quiz attempts
    Route::get('/quizzes/{quiz}/attempt', [QuizController::class, 'attempt'])->name('quizzes.attempt');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/{attempt}/result', [QuizController::class, 'result'])->name('quizzes.result');
    // My Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'my'])->name('activity-logs.my');


}
);

// ============================================================================
// GURU ONLY ROUTES (Hanya role guru)
// ============================================================================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {

    // ==================== COURSE MANAGEMENT (Kursus yang diajar) ====================
    // URL: /guru/courses
    Route::get('/courses', [GuruCourseController::class, 'index'])->name('courses.index');

    // ==================== ALL COURSES (Public catalog untuk guru) ====================
    // URL: /guru/courses/all
    Route::get('/courses/all', [GuruCourseController::class, 'all'])->name('courses.all');

    // ==================== COURSE DETAIL (Khusus guru) ====================
    // URL: /guru/courses/{id}
    Route::get('/courses/{id}', [GuruCourseController::class, 'show'])->name('courses.show');

    // ==================== STATS API ====================
    // URL: /guru/courses/stats
    Route::get('/courses/stats', [GuruCourseController::class, 'stats'])->name('courses.stats');

    // ==================== STUDENT MANAGEMENT (Siswa di kursus yang diajar) ====================
    // URL: /guru/students
    Route::get('/students', [GuruStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}', [GuruStudentController::class, 'show'])->name('students.show');
    Route::put('/students/{studentId}/enrollments/{enrollmentId}', [GuruStudentController::class, 'updateEnrollment'])->name('students.enrollments.update');
    Route::post('/students/bulk-update', [GuruStudentController::class, 'bulkUpdate'])->name('students.bulk-update');
    Route::get('/students/export', [GuruStudentController::class, 'export'])->name('students.export');
});

// ============================================================================
// ADMIN ONLY ROUTES (Hanya role admin)
// ============================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // ==================== DASHBOARD ====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== USER MANAGEMENT ====================
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

    // ==================== CATEGORY MANAGEMENT ====================
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

    // ==================== STUDENT MANAGEMENT (Admin melihat semua siswa) ====================
    Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}', [AdminStudentController::class, 'show'])->name('students.show');
    Route::get('/students/export', [AdminStudentController::class, 'export'])->name('students.export');

    // ==================== REPORTS ====================
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/courses', [ReportController::class, 'courses'])->name('reports.courses');
    Route::get('/reports/users', [ReportController::class, 'users'])->name('reports.users');
    Route::get('/reports/enrollments', [ReportController::class, 'enrollments'])->name('reports.enrollments');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // ==================== SYSTEM SETTINGS ====================
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // ==================== ACTIVITY LOGS ====================
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{log}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::delete('/activity-logs/clear', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');

    // ==================== BACKUP ====================
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download/{file}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/delete/{file}', [BackupController::class, 'delete'])->name('backup.delete');
});

// ============================================================================
// FORUM ROUTES
// ============================================================================
Route::prefix('forum')->name('forum.')->middleware(['auth'])->group(function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('/discussions/create', [ForumController::class, 'create'])->name('discussions.create');
    Route::post('/discussions', [ForumController::class, 'store'])->name('discussions.store');
    Route::get('/discussions/{discussion}', [ForumController::class, 'show'])->name('discussions.show');
    Route::post('/discussions/{discussion}/replies', [ForumController::class, 'reply'])->name('replies.store');
});

// ============================================================================
// API-LIKE ROUTES (AJAX)
// ============================================================================
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

// ============================================================================
// AUTH ROUTES (Dari Breeze/Laravel UI)
// ============================================================================
require __DIR__.'/auth.php';
