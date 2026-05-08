<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();

        // Alternatif: Ambil kursus langsung dari model Course
        // Guru hanya bisa melihat siswa di kursus yang dia ajar
        $teacherCourses = Course::where('guru_id', $teacher->id)->pluck('id');

        // Jika tidak ada kursus, return empty
        if ($teacherCourses->isEmpty()) {
            $students = collect();
            $courses = collect();
            return view('guru.students.index', compact('students', 'courses'));
        }

        // Ambil semua siswa yang enroll di kursus guru ini
        $query = User::where('role', 'siswa')
            ->whereHas('enrollments', function($q) use ($teacherCourses) {
                $q->whereIn('course_id', $teacherCourses);
            })
            ->with(['enrollments' => function($q) use ($teacherCourses) {
                $q->whereIn('course_id', $teacherCourses)->with('course');
            }]);

        // Filter berdasarkan pencarian nama/email
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan kursus tertentu
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

        // Ambil kursus guru untuk filter dropdown
        $courses = Course::where('guru_id', $teacher->id)
            ->where('status', 'published')
            ->get();

        return view('guru.students.index', compact('students', 'courses'));
    }

    public function show($id)
    {
        $teacher = Auth::user();

        // Ambil kursus yang diajar guru
        $teacherCourses = Course::where('guru_id', $teacher->id)->pluck('id');

        if ($teacherCourses->isEmpty()) {
            abort(404, 'Tidak ada data siswa');
        }

        $student = User::where('role', 'siswa')
            ->whereHas('enrollments', function($q) use ($teacherCourses) {
                $q->whereIn('course_id', $teacherCourses);
            })
            ->with(['enrollments' => function($q) use ($teacherCourses) {
                $q->whereIn('course_id', $teacherCourses)->with('course');
            }, 'certificates.course'])
            ->findOrFail($id);

        // Statistik untuk kursus guru ini saja
        $enrollmentsInTeacherCourses = $student->enrollments->filter(function($enrollment) use ($teacherCourses) {
            return $teacherCourses->contains($enrollment->course_id);
        });

        $totalEnrollments = $enrollmentsInTeacherCourses->count();
        $completedCourses = $enrollmentsInTeacherCourses->where('status', 'completed')->count();
        $averageProgress = $enrollmentsInTeacherCourses->avg('progress') ?? 0;

        $activeCourses = $enrollmentsInTeacherCourses->where('status', 'active');
        $completedCoursesList = $enrollmentsInTeacherCourses->where('status', 'completed');

        return view('guru.students.show', compact(
            'student',
            'totalEnrollments',
            'completedCourses',
            'averageProgress',
            'activeCourses',
            'completedCoursesList'
        ));
    }

    public function updateEnrollment(Request $request, $studentId, $enrollmentId)
    {
        $teacher = Auth::user();

        // Ambil kursus yang diajar guru
        $teacherCourses = Course::where('guru_id', $teacher->id)->pluck('id');

        if ($teacherCourses->isEmpty()) {
            abort(403, 'Anda tidak memiliki akses');
        }

        // Validasi bahwa enrollment ada dan milik kursus guru
        $enrollment = Enrollment::findOrFail($enrollmentId);

        if (!$teacherCourses->contains($enrollment->course_id)) {
            abort(403, 'Anda tidak memiliki akses ke enrollment ini');
        }

        // Validasi input
        $validated = $request->validate([
            'progress' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|in:active,completed,paused,cancelled',
        ]);

        // Update enrollment
        if (isset($validated['progress'])) {
            $enrollment->progress = $validated['progress'];
        }

        if (isset($validated['status'])) {
            $enrollment->status = $validated['status'];
            
            // Jika status berubah ke completed, set completed_at
            if ($validated['status'] === 'completed' && !$enrollment->completed_at) {
                $enrollment->completed_at = now();
            }
            // Jika status kembali ke active, reset completed_at
            elseif ($validated['status'] === 'active' && $enrollment->completed_at) {
                $enrollment->completed_at = null;
            }
        }

        $enrollment->save();

        return back()->with('success', 'Status enrollment siswa berhasil diperbarui');
    }

    public function bulkUpdate(Request $request)
    {
        $teacher = Auth::user();

        // Ambil kursus yang diajar guru
        $teacherCourses = Course::where('guru_id', $teacher->id)->pluck('id');

        if ($teacherCourses->isEmpty()) {
            return back()->with('error', 'Anda tidak memiliki akses');
        }

        // Validasi input
        $validated = $request->validate([
            'enrollment_ids' => 'required|array|min:1',
            'enrollment_ids.*' => 'integer|exists:enrollments,id',
            'action' => 'required|in:set_progress,set_status',
            'value' => 'required',
        ]);

        $enrollmentIds = $validated['enrollment_ids'];
        
        // Ambil semua enrollment yang akan diupdate
        $enrollments = Enrollment::whereIn('id', $enrollmentIds)
            ->whereIn('course_id', $teacherCourses)
            ->get();

        // Jika tidak ada enrollment yang sesuai, abort
        if ($enrollments->isEmpty()) {
            return back()->with('error', 'Tidak ada enrollment yang sesuai dengan akses Anda');
        }

        $action = $validated['action'];
        $value = $validated['value'];
        $updated = 0;

        foreach ($enrollments as $enrollment) {
            if ($action === 'set_progress') {
                $progress = (int) $value;
                if ($progress >= 0 && $progress <= 100) {
                    $enrollment->progress = $progress;
                    $enrollment->save();
                    $updated++;
                }
            } elseif ($action === 'set_status') {
                if (in_array($value, ['active', 'completed', 'paused', 'cancelled'])) {
                    $enrollment->status = $value;
                    
                    if ($value === 'completed' && !$enrollment->completed_at) {
                        $enrollment->completed_at = now();
                    } elseif ($value === 'active' && $enrollment->completed_at) {
                        $enrollment->completed_at = null;
                    }
                    
                    $enrollment->save();
                    $updated++;
                }
            }
        }

        $message = $updated > 0 
            ? "Berhasil update $updated enrollment"
            : 'Tidak ada enrollment yang diupdate';

        return back()->with('success', $message);
    }

    public function export(Request $request)
    {
        $teacher = Auth::user();

        // Ambil kursus yang diajar guru
        $teacherCourses = Course::where('guru_id', $teacher->id)->pluck('id');

        if ($teacherCourses->isEmpty()) {
            return back()->with('error', 'Tidak ada data siswa untuk diexport');
        }

        $query = User::where('role', 'siswa')
            ->whereHas('enrollments', function($q) use ($teacherCourses) {
                $q->whereIn('course_id', $teacherCourses);
            });

        if ($request->has('course_id') && $request->course_id) {
            $query->whereHas('enrollments', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        $students = $query->get();

        $filename = 'my-students-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w');

        // Headers
        fputcsv($handle, [
            'ID', 'Nama', 'Email', 'Kursus', 'Status', 'Progres', 'Bergabung', 'Selesai Pada'
        ]);

        foreach ($students as $student) {
            foreach ($student->enrollments as $enrollment) {
                if ($teacherCourses->contains($enrollment->course_id)) {
                    fputcsv($handle, [
                        $student->id,
                        $student->name,
                        $student->email,
                        $enrollment->course->judul,
                        $enrollment->status,
                        $enrollment->progress . '%',
                        $enrollment->enrolled_at->format('Y-m-d'),
                        $enrollment->completed_at ? $enrollment->completed_at->format('Y-m-d') : '-',
                    ]);
                }
            }
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
