<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Certificate;

class ReportController extends Controller
{
    public function index()
    {
        // Summary statistics
        $totalUsers = User::count();
        $totalGurus = User::where('role', 'guru')->count();
        $totalSiswas = User::where('role', 'siswa')->count();
        $totalCourses = Course::count();
        $totalPublishedCourses = Course::where('status', 'published')->count();
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        $totalCertificates = Certificate::count();
        $totalRevenue = Course::sum('harga');

        // Monthly trends (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'users' => User::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)->count(),
                'courses' => Course::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)->count(),
                'enrollments' => Enrollment::whereMonth('enrolled_at', $month->month)
                    ->whereYear('enrolled_at', $month->year)->count(),
            ];
        }

        // Top courses by enrollment
        $topCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->limit(5)
            ->get();

        // Recent activities
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalUsers', 'totalGurus', 'totalSiswas',
            'totalCourses', 'totalPublishedCourses',
            'totalEnrollments', 'completedEnrollments',
            'totalCertificates', 'totalRevenue',
            'monthlyData', 'topCourses', 'recentEnrollments'
        ));
    }

    public function courses()
    {
        $courses = Course::with(['guru', 'kategori'])
            ->withCount(['enrollments', 'lessons'])
            ->withSum('enrollments', 'progress')
            ->orderBy('enrollments_count', 'desc')
            ->paginate(20);

        return view('admin.reports.courses', compact('courses'));
    }

    public function users()
    {
        $users = User::withCount(['enrollments', 'certificates', 'quizAttempts'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $roleStats = [
            'admin' => User::where('role', 'admin')->count(),
            'guru' => User::where('role', 'guru')->count(),
            'siswa' => User::where('role', 'siswa')->count(),
        ];

        return view('admin.reports.users', compact('users', 'roleStats'));
    }

    public function enrollments()
    {
        $enrollments = Enrollment::with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $completionRate = Enrollment::where('status', 'completed')->count();
        $totalEnrollments = Enrollment::count();
        $avgProgress = Enrollment::avg('progress');

        return view('admin.reports.enrollments', compact('enrollments', 'completionRate', 'totalEnrollments', 'avgProgress'));
    }

    public function revenue()
    {
        $totalRevenue = Course::sum('harga');
        $freeCourses = Course::where('harga', 0)->count();
        $paidCourses = Course::where('harga', '>', 0)->count();
        $avgPrice = Course::where('harga', '>', 0)->avg('harga');

        $revenueByMonth = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->select(
                DB::raw('DATE_FORMAT(enrollments.created_at, "%Y-%m") as month'),
                DB::raw('SUM(courses.harga) as total')
            )
            ->where('courses.harga', '>', 0)
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        $topRevenueCourses = Course::where('harga', '>', 0)
            ->withCount('enrollments')
            ->orderBy('harga', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.revenue', compact(
            'totalRevenue', 'freeCourses', 'paidCourses', 'avgPrice',
            'revenueByMonth', 'topRevenueCourses'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'courses');

        switch ($type) {
            case 'courses':
                return $this->exportCourses();
            case 'users':
                return $this->exportUsers();
            case 'enrollments':
                return $this->exportEnrollments();
            default:
                return back()->with('error', 'Tipe export tidak valid');
        }
    }

    private function exportCourses()
    {
        $courses = Course::with(['guru', 'kategori'])
            ->withCount('enrollments')
            ->get();

        $filename = 'courses-report-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w');

        // Headers
        fputcsv($handle, ['ID', 'Judul', 'Guru', 'Kategori', 'Harga', 'Status', 'Enrollments', 'Dibuat']);

        // Data
        foreach ($courses as $course) {
            fputcsv($handle, [
                $course->id,
                $course->judul,
                $course->guru->name,
                $course->kategori->nama,
                $course->harga,
                $course->status,
                $course->enrollments_count,
                $course->created_at->format('Y-m-d'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    private function exportUsers()
    {
        $users = User::all();

        $filename = 'users-report-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w');

        fputcsv($handle, ['ID', 'Nama', 'Email', 'Role', 'Bio', 'Bergabung']);

        foreach ($users as $user) {
            fputcsv($handle, [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->bio,
                $user->created_at->format('Y-m-d'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    private function exportEnrollments()
    {
        $enrollments = Enrollment::with(['user', 'course'])->get();

        $filename = 'enrollments-report-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w');

        fputcsv($handle, ['ID', 'Siswa', 'Kursus', 'Status', 'Progress', 'Enrolled At', 'Completed At']);

        foreach ($enrollments as $enrollment) {
            fputcsv($handle, [
                $enrollment->id,
                $enrollment->user->name,
                $enrollment->course->judul,
                $enrollment->status,
                $enrollment->progress . '%',
                $enrollment->enrolled_at->format('Y-m-d'),
                $enrollment->completed_at ? $enrollment->completed_at->format('Y-m-d') : '-',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
    // public function index()
    // {
    //     $totalUsers = User::count();
    //     $totalCourses = Course::count();
    //     $totalEnrollments = Enrollment::count();
    //     $completedCourses = Enrollment::where('status', 'completed')->count();

    //     return view('admin.reports.index', compact('totalUsers', 'totalCourses', 'totalEnrollments', 'completedCourses'));
    // }

    // public function courses()
    // {
    //     $courses = Course::withCount('enrollments')
    //         ->orderBy('enrollments_count', 'desc')
    //         ->paginate(20);

    //     return view('admin.reports.courses', compact('courses'));
    // }

    // public function users()
    // {
    //     $users = User::withCount(['enrollments', 'certificates'])
    //         ->orderBy('enrollments_count', 'desc')
    //         ->paginate(20);

    //     return view('admin.reports.users', compact('users'));
    // }

    // public function enrollments()
    // {
    //     $enrollments = Enrollment::with(['user', 'course'])
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(20);

    //     return view('admin.reports.enrollments', compact('enrollments'));
    // }

    // public function revenue()
    // {
    //     $totalRevenue = Course::sum('harga');
    //     $paidCourses = Course::where('harga', '>', 0)->count();
    //     $freeCourses = Course::where('harga', 0)->count();

    //     return view('admin.reports.revenue', compact('totalRevenue', 'paidCourses', 'freeCourses'));
    // }

    // public function export()
    // {
    //     // Implement export logic
    //     return back()->with('success', 'Report export akan segera diproses.');
    // }
}
