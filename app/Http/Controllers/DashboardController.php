<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }
        elseif ($user->isGuru()) {
            return $this->guruDashboard();
        }
        else {
            return $this->siswaDashboard();
        }
    }

    private function adminDashboard()
    {
        $totalUsers = User::count();
        $totalGurus = User::where('role', 'guru')->count();
        $totalSiswas = User::where('role', 'siswa')->count();
        $totalCourses = Course::count();
        $totalPublishedCourses = Course::where('status', 'published')->count();
        $totalEnrollments = Enrollment::count();
        $totalCertificates = \App\Models\Certificate::count();

        // Statistik pendapatan (jika ada kursus berbayar)
        $totalRevenue = Course::sum('harga');

        // Kursus terbaru
        $recentCourses = Course::with(['guru', 'kategori'])
            ->latest()
            ->take(5)
            ->get();

        // User terbaru
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Statistik per bulan (6 bulan terakhir)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $month->format('M Y'),
                'users' => User::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count(),
                'courses' => Course::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count(),
                'enrollments' => Enrollment::whereMonth('enrolled_at', $month->month)
                    ->whereYear('enrolled_at', $month->year)
                    ->count(),
            ];
        }

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalGurus',
            'totalSiswas',
            'totalCourses',
            'totalPublishedCourses',
            'totalEnrollments',
            'totalCertificates',
            'totalRevenue',
            'recentCourses',
            'recentUsers',
            'monthlyStats'
        ));
    }

    private function guruDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Kursus yang dibuat guru
        $courses = $user->coursesTaught()->withCount('enrollments')->get();

        // Total siswa yang enroll di kursus guru
        $totalStudents = Enrollment::whereIn('course_id', $courses->pluck('id'))
            ->distinct('user_id')
            ->count('user_id');

        // Total pendapatan dari kursus berbayar
        $totalRevenue = $courses->sum('harga');

        // Kuis yang perlu dinilai
        $pendingGrading = QuizAttempt::whereHas('quiz.course', function($query) use ($user) {
                $query->where('guru_id', $user->id);
            })
            ->where('is_graded', false)
            ->whereNotNull('submitted_at')
            ->count();

        // Statistik per kursus
        $courseStats = [];
        foreach ($courses as $course) {
            $completedEnrollments = $course->enrollments()
                ->where('status', 'completed')
                ->count();

            $courseStats[] = [
                'course' => $course,
                'total_enrolled' => $course->enrollments_count,
                'completed' => $completedEnrollments,
                'completion_rate' => $course->enrollments_count > 0
                    ? round(($completedEnrollments / $course->enrollments_count) * 100, 2)
                    : 0,
                'avg_progress' => $course->enrollments()->avg('progress') ?? 0,
            ];
        }

        // 5 siswa teraktif
        $topStudents = Enrollment::whereIn('course_id', $courses->pluck('id'))
            ->with('user')
            ->select('user_id', DB::raw('AVG(progress) as avg_progress'))
            ->groupBy('user_id')
            ->orderBy('avg_progress', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.guru', compact(
            'courses',
            'totalStudents',
            'totalRevenue',
            'pendingGrading',
            'courseStats',
            'topStudents'
        ));
    }

    private function siswaDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Kursus yang diambil
        $enrollments = $user->enrollments()
            ->with('course.guru')
            ->orderBy('created_at', 'desc')
            ->get();

        // Sertifikat yang didapat
        $certificates = $user->certificates()
            ->with('course')
            ->orderBy('issued_at', 'desc')
            ->get();

        // Statistik belajar
        $totalCoursesEnrolled = $enrollments->count();
        $completedCourses = $enrollments->where('status', 'completed')->count();
        $averageProgress = $enrollments->avg('progress') ?? 0;

        // Kuis yang sudah dikerjakan
        $quizAttempts = QuizAttempt::where('user_id', $user->id)
            ->with('quiz.course')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Rekomendasi kursus (kursus published yang belum diambil)
        $enrolledCourseIds = $enrollments->pluck('course_id');
        $recommendedCourses = Course::where('status', 'published')
            ->whereNotIn('id', $enrolledCourseIds)
            ->with(['guru', 'kategori'])
            ->latest()
            ->take(3)
            ->get();

        // Progres belajar per kursus
        $learningProgress = [];
        foreach ($enrollments as $enrollment) {
            $learningProgress[] = [
                'course' => $enrollment->course,
                'progress' => $enrollment->progress,
                'status' => $enrollment->status,
                'last_activity' => $enrollment->updated_at,
            ];
        }

        return view('dashboard.siswa', compact(
            'enrollments',
            'certificates',
            'totalCoursesEnrolled',
            'completedCourses',
            'averageProgress',
            'quizAttempts',
            'recommendedCourses',
            'learningProgress'
        ));
    }
}
