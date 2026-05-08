<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Course::class, 'course');
    // }

    public function index(Request $request)
    {
        $query = Course::where('status', 'published')
            ->with(['guru', 'kategori']);

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category) {
            $query->where('kategori_id', $request->category);
        }

        // Filter berdasarkan harga (gratis/berbayar)
        if ($request->has('price')) {
            if ($request->price == 'free') {
                $query->where('harga', 0);
            } elseif ($request->price == 'paid') {
                $query->where('harga', '>', 0);
            }
        }

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('harga', 'desc');
                break;
            default:
                $query->latest();
        }

        $courses = $query->paginate(12);
        $categories = Category::all();

        return view('courses.index', compact('courses', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required|exists:categories,id',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $validated['guru_id'] = Auth::id();

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $course = Course::create($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Kursus berhasil dibuat.');
    }

    public function show(Course $course)
    {
        // Cek apakah kursus bisa diakses
        if ($course->status === 'draft') {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if (!Auth::check() || (Auth::id() !== $course->guru_id && !$user->isAdmin())) {
                abort(404);
            }
        }

        $enrollment = null;
        $progress = 0;
        $canTakeQuiz = false;
        $quiz = $course->quizzes()->first();

        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($user->isSiswa()) {
                $enrollment = $course->enrollments()->where('user_id', Auth::id())->first();
                if ($enrollment) {
                    $progress = $enrollment->progress;
                    $canTakeQuiz = $enrollment->status === 'completed' && $quiz;
                }
            }
        }

        $isEnrolled = $enrollment ? true : false;
        $totalLessons = $course->lessons()->count();
        $completedLessons = $enrollment ? $enrollment->lessonCompletions()->count() : 0;

        return view('courses.show', compact(
            'course',
            'enrollment',
            'progress',
            'canTakeQuiz',
            'quiz',
            'isEnrolled',
            'totalLessons',
            'completedLessons'
        ));
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        $categories = Category::all();
        return view('courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required|exists:categories,id',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        $course->update($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Kursus berhasil diupdate.');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        // Hapus thumbnail
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        // Hapus semua PDF lessons
        foreach ($course->lessons as $lesson) {
            if ($lesson->file_pdf) {
                Storage::disk('public')->delete($lesson->file_pdf);
            }
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Kursus berhasil dihapus.');
    }

    /**
     * Duplicate a course
     */
    public function duplicate(Course $course)
    {
        $newCourse = $course->replicate();
        $newCourse->judul = $course->judul . ' (Copy)';
        $newCourse->status = 'draft';
        $newCourse->save();

        return redirect()->route('courses.show', $newCourse)
            ->with('success', 'Kursus berhasil diduplikasi.');
    }

    /**
     * Publish a course
     */
    public function publish(Course $course)
    {
        $this->authorize('update', $course);

        $course->update(['status' => 'published']);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Kursus berhasil dipublikasikan.');
    }

    /**
     * Unpublish a course
     */
    public function unpublish(Course $course)
    {
        $this->authorize('update', $course);

        $course->update(['status' => 'draft']);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Kursus berhasil disembunyikan.');
    }

    /**
     * Search courses
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $courses = Course::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('judul', 'like', '%' . $query . '%')
                  ->orWhere('deskripsi', 'like', '%' . $query . '%');
            })
            ->with(['guru', 'kategori'])
            ->limit(10)
            ->get();

        return response()->json($courses);
    }

    /**
     * Show course leaderboard
     */
    public function leaderboard(Course $course)
    {
        $leaderboard = $course->enrollments()
            ->where('status', 'completed')
            ->with('user')
            ->orderBy('completed_at', 'desc')
            ->paginate(10);

        return view('courses.leaderboard', compact('course', 'leaderboard'));
    }

    /**
     * Show my courses (courses where user is enrolled)
     */
    public function myCourses(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $query = $user->enrollments()
            ->with(['course' => function($q) {
                $q->with(['guru', 'kategori']);
            }])
            ->where('status', '!=', 'dropped')
            ->latest('enrolled_at');

        // Search
        if ($request->has('search') && $request->search) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->paginate(12);

        return view('courses.my', compact('enrollments'));
    }

}
