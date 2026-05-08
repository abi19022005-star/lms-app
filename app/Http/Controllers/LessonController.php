<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Course $course)
    {
        $this->authorize('update', $course);
        return view('lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:video,teks,pdf',
            'konten_teks' => 'nullable|string',
            'url_video' => 'nullable|required_if:tipe,video|url',
            'file_pdf' => 'nullable|required_if:tipe,pdf|file|mimes:pdf|max:51200',
            'material_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar|max:102400',
        ]);

        $validated['course_id'] = $course->id;

        // Handle file uploads
        if ($request->hasFile('file_pdf')) {
            $path = $request->file('file_pdf')->store('pdfs', 'public');
            $validated['file_pdf'] = $path;
        } else {
            // Remove file_pdf from validated if not uploaded
            $validated['file_pdf'] = null;
        }

        if ($request->hasFile('material_file')) {
            $path = $request->file('material_file')->store('materials', 'public');
            $validated['material_file'] = $path;
        } else {
            // Remove material_file from validated if not uploaded
            $validated['material_file'] = null;
        }

        try {
            Lesson::create($validated);
            return redirect()->route('courses.show', $course)
                ->with('success', 'Lesson berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
        $rules = [
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:video,teks,pdf',
            'material_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar|max:102400',
        ];

        // VALIDASI BERDASARKAN TIPE
        if ($request->tipe === 'teks') {
            $rules['konten_teks'] = 'required|string';
        }

        if ($request->tipe === 'video') {
            $rules['url_video'] = 'required|url';
        }

        if ($request->tipe === 'pdf') {
            $rules['file_pdf'] = 'required|file|mimes:pdf|max:51200';
        }

        $validated = $request->validate($rules);
    }

    public function edit(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);
        return view('lessons.edit', compact('lesson', 'course'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:video,teks,pdf',
            'konten_teks' => 'nullable|string',
            'url_video' => 'nullable|required_if:tipe,video|url',
            'file_pdf' => 'nullable|required_if:tipe,pdf|file|mimes:pdf|max:51200',
            'material_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar|max:102400',
        ]);

        // Handle file_pdf
        if ($request->hasFile('file_pdf')) {
            if ($lesson->file_pdf) {
                Storage::disk('public')->delete($lesson->file_pdf);
            }
            $path = $request->file('file_pdf')->store('pdfs', 'public');
            $validated['file_pdf'] = $path;
        } else {
            // Don't overwrite file_pdf if not updating it
            unset($validated['file_pdf']);
        }

        // Handle material_file
        if ($request->hasFile('material_file')) {
            if ($lesson->material_file) {
                Storage::disk('public')->delete($lesson->material_file);
            }
            $path = $request->file('material_file')->store('materials', 'public');
            $validated['material_file'] = $path;
        } elseif ($request->boolean('remove_material') && $lesson->material_file) {
            Storage::disk('public')->delete($lesson->material_file);
            $validated['material_file'] = null;
        } else {
            // Don't overwrite material_file if not updating it
            unset($validated['material_file']);
        }

        try {
            $lesson->update($validated);
            return redirect()->route('courses.show', $lesson->course)
                ->with('success', 'Lesson berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        if ($lesson->file_pdf) {
            Storage::disk('public')->delete($lesson->file_pdf);
        }

        if ($lesson->material_file) {
            Storage::disk('public')->delete($lesson->material_file);
        }

        $lesson->delete();

        return redirect()->route('courses.show', $lesson->course)
            ->with('success', 'Lesson berhasil dihapus.');
    }

    public function reorder(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|exists:lessons,id',
        ]);

        foreach ($request->order as $index => $lessonId) {
            Lesson::where('id', $lessonId)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function show(Lesson $lesson)
    {
        // Cek akses: hanya siswa yang sudah enroll atau guru pemilik
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $course = $lesson->course;

        if ($user->isSiswa()) {
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->exists();

            if (!$isEnrolled) {
                abort(403, 'Anda belum enroll kursus ini.');
            }
        } elseif ($user->isGuru()) {
            if ($course->guru_id !== $user->id && !$user->isAdmin()) {
                abort(403);
            }
        } else {
            abort(403);
        }

        // Ambil completion user untuk lesson ini
        $completion = LessonCompletion::where('user_id', Auth::user()->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        // Ambil lesson sebelumnya dan berikutnya berdasarkan 'order'
        $prevLesson = Lesson::where('course_id', $course->id)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();

        $nextLesson = Lesson::where('course_id', $course->id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        // Ambil semua lesson untuk sidebar
        $lessons = $course->lessons()->with(['completions' => function($q) {
            $q->where('user_id', Auth::id());
        }])->get();

        // Hitung progress kursus
        $totalLessons = $lessons->count();
        $completedLessons = LessonCompletion::where('user_id', Auth::user()->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->count();

            $courseProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        return view('lessons.show', compact(
            'course','lesson', 'completion', 'prevLesson', 'nextLesson',
             'lessons', 'totalLessons', 'completedLessons', 'courseProgress','totalLessons'));
    }

    /**
     * Preview a lesson (public preview)
     */
    public function preview(Lesson $lesson)
    {
        // Public preview if course status is published
        $course = $lesson->course;

        if ($course->status !== 'published') {
            abort(404);
        }

        return view('lessons.preview', compact('lesson'));
    }

    /**
     * Download lesson PDF
     */
    public function downloadPdf(Lesson $lesson)
    {
        $user = Auth::user();
        $course = $lesson->course;

        // Cek apakah user sudah enroll
        // Cek akses: hanya siswa yang sudah enroll atau guru pemilik
        /** @var \App\Models\User $user */
        if ($user->isSiswa()) {
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->exists();

            if (!$isEnrolled) {
                abort(403, 'Anda belum enroll kursus ini.');
            }
        }

        if (!$lesson->file_pdf) {
            return redirect()->back()->with('error', 'File PDF tidak tersedia untuk lesson ini.');
        }

        return response()->download(
            storage_path('app/public/' . $lesson->file_pdf)
        );
    }
    public function complete(Course $course, Lesson $lesson)
    {
        // Cek apakah sudah pernah complete
        $existingCompletion = LessonCompletion::where('user_id', Auth::user()->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        if (!$existingCompletion) {
            LessonCompletion::create([
                'user_id' => Auth::user()->id,
                'lesson_id' => $lesson->id,
                'completed_at' => now(),
            ]);
        }

        // Hitung ulang progress
        $totalLessons = $course->lessons()->count();
        $completedLessons = LessonCompletion::where('user_id', Auth::user()->id)
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->count();

        // Jika semua selesai, generate certificate
        if ($totalLessons == $completedLessons) {
            // Generate certificate di sini
            // Certificate::create([...]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lesson completed!',
            'course_progress' => round(($completedLessons / $totalLessons) * 100)
        ]);
    }

    /**
     * Guru menandai lesson siswa sebagai selesai
     */
    public function markComplete(Course $course, Lesson $lesson, User $user)
    {
        // Cek apakah guru pemilik course atau admin
        $this->authorize('update', $course);

        // Cek apakah siswa sudah menyelesaikan
        $existingCompletion = LessonCompletion::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        if (!$existingCompletion) {
            LessonCompletion::create([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'completed_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Pembelajaran siswa telah ditandai selesai.');
        }

        return redirect()->back()->with('info', 'Pembelajaran ini sudah ditandai selesai sebelumnya.');
    }
}
