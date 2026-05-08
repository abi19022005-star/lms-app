<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of all students with their enrolled courses.
     */
    public function index(Request $request)
    {
        // Admin: Ambil semua siswa (role = siswa) tanpa batasan kursus
        $query = User::where('role', 'siswa')->with(['enrollments.course.guru']);

        // Filter berdasarkan pencarian nama/email
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan kursus (bisa pilih kursus apapun)
        if ($request->has('course_id') && $request->course_id) {
            $query->whereHas('enrollments', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        // Filter berdasarkan status enrollment
        if ($request->has('status') && $request->status) {
            $query->whereHas('enrollments', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $students = $query->paginate(20);

        // Admin: Ambil semua kursus untuk filter dropdown (tanpa batasan guru)
        $courses = Course::where('status', 'published')->get();

        return view('admin.students.index', compact('students', 'courses'));
    }

    /**
     * Show detailed information of a specific student.
     */
    public function show($id)
    {
        // Admin: Bisa melihat detail siswa manapun
        $student = User::where('role', 'siswa')
            ->with(['enrollments.course.guru', 'certificates.course', 'quizAttempts.quiz'])
            ->findOrFail($id);

        // Statistik siswa
        $totalEnrollments = $student->enrollments->count();
        $completedCourses = $student->enrollments->where('status', 'completed')->count();
        $totalCertificates = $student->certificates->count();
        $averageProgress = $student->enrollments->avg('progress') ?? 0;

        // Kursus yang sedang aktif
        $activeCourses = $student->enrollments->where('status', 'active');

        // Kursus yang sudah selesai
        $completedCoursesList = $student->enrollments->where('status', 'completed');

        return view('admin.students.show', compact(
            'student',
            'totalEnrollments',
            'completedCourses',
            'totalCertificates',
            'averageProgress',
            'activeCourses',
            'completedCoursesList'
        ));
    }

    /**
     * Export all students data to CSV.
     */
    public function export(Request $request)
    {
        // Admin: Export semua siswa
        $query = User::where('role', 'siswa')->with(['enrollments.course']);

        if ($request->has('course_id') && $request->course_id) {
            $query->whereHas('enrollments', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        $students = $query->get();

        $filename = 'students-data-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w');

        // Headers
        fputcsv($handle, [
            'ID',
            'Nama',
            'Email',
            'Bergabung',
            'Total Kursus Diambil',
            'Kursus Selesai',
            'Rata-rata Progres',
            'Sertifikat',
            'Daftar Kursus'
        ]);

        foreach ($students as $student) {
            $coursesList = $student->enrollments->map(function($enrollment) {
                return $enrollment->course->judul . ' (' . $enrollment->progress . '%)';
            })->implode('; ');

            fputcsv($handle, [
                $student->id,
                $student->name,
                $student->email,
                $student->created_at->format('Y-m-d'),
                $student->enrollments->count(),
                $student->enrollments->where('status', 'completed')->count(),
                round($student->enrollments->avg('progress') ?? 0, 1) . '%',
                $student->certificates->count(),
                $coursesList,
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
}
