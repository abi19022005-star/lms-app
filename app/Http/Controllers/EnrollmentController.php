<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;
use App\Mail\EnrollmentConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function enroll(Course $course)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isSiswa()) {
            return redirect()->back()->with('error', 'Hanya siswa yang dapat enroll kursus.');
        }

        // Cek sudah enroll
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->route('courses.show', $course)
                ->with('info', 'Anda sudah terdaftar di kursus ini.');
        }

        // Cek status kursus
        if ($course->status !== 'published') {
            return redirect()->back()->with('error', 'Kursus belum dipublikasikan.');
        }

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'active',
            'progress' => 0,
            'enrolled_at' => now(),
        ]);

        logActivity([
            'action' => 'enroll',
            'action_type' => 'CREATE',
            'module' => 'course',
            'model_type' => \App\Models\Course::class,
            'model_id' => $course->id,
            'model_name' => $course->judul,
            'description' => 'User enroll ke course',
        ]);
        // Kirim email konfirmasi
         try {
            Mail::to($user->email)->queue(new EnrollmentConfirmation($enrollment));
        } catch (\Exception $e) {
            Log::error('Email gagal dikirim: ' . $e->getMessage());
        }

        return redirect()->route('courses.show', $course)
            ->with('success', 'Berhasil enroll kursus! Selamat belajar.');
    }

    public function completeLesson(Lesson $lesson)
    {
        $user = Auth::user();

        // Cek apakah user sudah enroll
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $lesson->course_id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum enroll kursus ini.'
            ], 403);
        }

        // Cek apakah lesson sudah pernah diselesaikan
        $existingCompletion = LessonCompletion::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        if ($existingCompletion) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson sudah ditandai selesai sebelumnya.'
            ], 400);
        }

        // Tandai selesai
        $completion = LessonCompletion::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'completed_at' => now(),
        ]);

        // Update progress enrollment
        $progress = $enrollment->refreshProgress();

        $message = $progress == 100
            ? 'Selamat! Anda telah menyelesaikan semua lesson. Silakan ikuti kuis untuk mendapatkan sertifikat.'
            : 'Lesson ditandai selesai. Progres Anda: ' . $progress . '%';

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'is_completed' => $progress == 100,
            'message' => $message,
        ]);
    }

    public function progress(Course $course)
    {
        $user = Auth::user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $completedLessons = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->with('lesson')
            ->get();

        return response()->json([
            'progress' => $enrollment->progress,
            'status' => $enrollment->status,
            'completed_lessons' => $completedLessons->count(),
            'total_lessons' => $course->lessons->count(),
            'completed_lesson_details' => $completedLessons,
        ]);
    }

    /**
     * Get progress via AJAX
     */
    public function getProgress(Course $course)
    {
        return $this->progress($course);
    }

    /**
     * Show students enrolled in a course
     */
    public function students(Course $course)
    {
        $this->authorize('update', $course);

        $students = $course->enrollments()
            ->with('user')
            ->paginate(20);

        return view('courses.students', compact('course', 'students'));
    }

    /**
     * Export students list to CSV
     */
    public function exportStudents(Course $course)
    {
        $this->authorize('update', $course);

        $students = $course->enrollments()
            ->with('user')
            ->get();

        $filename = 'students-' . $course->id . '-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function() use ($students) {
            echo "Name,Email,Status,Progress,Enrolled Date\n";

            foreach ($students as $enrollment) {
                echo $enrollment->user->name . ','
                    . $enrollment->user->email . ','
                    . $enrollment->status . ','
                    . $enrollment->progress . '%,'
                    . $enrollment->enrolled_at->format('Y-m-d H:i') . "\n";
            }
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}

