<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ambil semua course yang diikuti user
        $enrollments = Enrollment::with(['course.lessons'])
            ->where('user_id', $user->id)
            ->get();

        $progressData = $enrollments->map(function ($enroll) {

            $totalLessons = $enroll->course->lessons->count();

            $completedLessons = $enroll->progress ?? 0; // sesuaikan field kamu

            $percentage = $totalLessons > 0
                ? ($completedLessons / $totalLessons) * 100
                : 0;

            return [
                'course' => $enroll->course,
                'total' => $totalLessons,
                'completed' => $completedLessons,
                'progress' => round($percentage)
            ];
        });

        return view('progress.index', compact('progressData'));
    }
}
