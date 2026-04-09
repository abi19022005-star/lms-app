<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
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
            'konten_teks' => 'required_if:tipe,teks|nullable|string',
            'url_video' => 'required_if:tipe,video|nullable|url',
            'file_pdf' => 'required_if:tipe,pdf|nullable|file|mimes:pdf|max:51200',
        ]);

        $validated['course_id'] = $course->id;

        if ($request->hasFile('file_pdf')) {
            $path = $request->file('file_pdf')->store('pdfs', 'public');
            $validated['file_pdf'] = $path;
        }

        Lesson::create($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Lesson berhasil ditambahkan.');
    }

    public function edit(Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);
        return view('lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:video,teks,pdf',
            'konten_teks' => 'required_if:tipe,teks|nullable|string',
            'url_video' => 'required_if:tipe,video|nullable|url',
            'file_pdf' => 'nullable|file|mimes:pdf|max:51200',
        ]);

        if ($request->hasFile('file_pdf')) {
            if ($lesson->file_pdf) {
                Storage::disk('public')->delete($lesson->file_pdf);
            }
            $path = $request->file('file_pdf')->store('pdfs', 'public');
            $validated['file_pdf'] = $path;
        }

        $lesson->update($validated);

        return redirect()->route('courses.show', $lesson->course)
            ->with('success', 'Lesson berhasil diupdate.');
    }

    public function destroy(Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        if ($lesson->file_pdf) {
            Storage::disk('public')->delete($lesson->file_pdf);
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

        return view('lessons.show', compact('lesson'));
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

        return Storage::disk('public')->download($lesson->file_pdf);
    }
}
