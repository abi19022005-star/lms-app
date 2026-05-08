<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of teacher's own courses.
     * Menampilkan hanya kursus yang diajar oleh guru tersebut
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();

        // Query: Hanya kursus milik guru ini
        $query = Course::where('guru_id', $teacher->id)
            ->with(['guru', 'kategori'])
            ->withCount(['enrollments', 'lessons']);

        // Filter berdasarkan status (draft/published)
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category) {
            $query->where('kategori_id', $request->category);
        }

        // Search berdasarkan judul
        if ($request->has('search') && $request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title_asc':
                $query->orderBy('judul', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('judul', 'desc');
                break;
            case 'most_enrolled':
                $query->orderBy('enrollments_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $courses = $query->paginate(12);

        // Data untuk filter
        $categories = Category::all();
        $totalCourses = $query->count();
        $publishedCourses = Course::where('guru_id', $teacher->id)->where('status', 'published')->count();
        $draftCourses = Course::where('guru_id', $teacher->id)->where('status', 'draft')->count();
        $totalStudents = Course::where('guru_id', $teacher->id)
            ->withCount('enrollments')
            ->get()
            ->sum('enrollments_count');

        return view('guru.courses.index', compact(
            'courses',
            'categories',
            'totalCourses',
            'publishedCourses',
            'draftCourses',
            'totalStudents'
        ));
    }

    /**
     * Display all published courses (like public catalog).
     * Menampilkan semua kursus yang dipublikasikan
     */
    public function all(Request $request)
    {
        // Query: Semua kursus yang dipublikasikan (tanpa batasan guru)
        $query = Course::where('status', 'published')
            ->with(['guru', 'kategori'])
            ->withCount(['enrollments', 'lessons']);

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category) {
            $query->where('kategori_id', $request->category);
        }

        // Filter berdasarkan harga (gratis/berbayar)
        if ($request->has('price') && $request->price) {
            if ($request->price == 'free') {
                $query->where('harga', 0);
            } elseif ($request->price == 'paid') {
                $query->where('harga', '>', 0);
            }
        }

        // Search berdasarkan judul
        if ($request->has('search') && $request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('enrollments_count', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('harga', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('judul', 'asc');
                break;
            default:
                $query->latest();
        }

        $courses = $query->paginate(12);

        // Data untuk filter
        $categories = Category::all();

        return view('guru.courses.all', compact('courses', 'categories'));
    }

    /**
     * Show details of a specific course (for teacher view).
     */
    public function show($id)
    {
        $teacher = Auth::user();

        $course = Course::where('id', $id)
            ->where('guru_id', $teacher->id) // Hanya guru pemilik yang bisa lihat detail ini
            ->with(['guru', 'kategori', 'lessons', 'enrollments.user', 'quizzes.questions'])
            ->firstOrFail();

        // Statistik kursus
        $totalEnrollments = $course->enrollments->count();
        $completedEnrollments = $course->enrollments->where('status', 'completed')->count();
        $averageProgress = $course->enrollments->avg('progress') ?? 0;
        $totalLessons = $course->lessons->count();
        $totalQuizzes = $course->quizzes->count();
        $totalQuestions = $course->quizzes->sum(function($quiz) {
            return $quiz->questions->count();
        });

        // 5 siswa terbaru yang enroll
        $recentStudents = $course->enrollments()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('guru.courses.show', compact(
            'course',
            'totalEnrollments',
            'completedEnrollments',
            'averageProgress',
            'totalLessons',
            'totalQuizzes',
            'totalQuestions',
            'recentStudents'
        ));
    }

    /**
     * Get statistics for dashboard.
     */
    public function stats()
    {
        $teacher = Auth::user();

        $stats = [
            'total_courses' => Course::where('guru_id', $teacher->id)->count(),
            'published_courses' => Course::where('guru_id', $teacher->id)->where('status', 'published')->count(),
            'draft_courses' => Course::where('guru_id', $teacher->id)->where('status', 'draft')->count(),
            'total_students' => Course::where('guru_id', $teacher->id)
                ->withCount('enrollments')
                ->get()
                ->sum('enrollments_count'),
            'total_revenue' => Course::where('guru_id', $teacher->id)->sum('harga'),
            'total_lessons' => Course::where('guru_id', $teacher->id)
                ->withCount('lessons')
                ->get()
                ->sum('lessons_count'),
        ];

        return response()->json($stats);
    }
}
