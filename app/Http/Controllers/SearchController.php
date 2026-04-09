<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'courses' => [],
                'lessons' => [],
            ]);
        }

        // Search Courses
        $courses = Course::where('judul', 'LIKE', "%{$query}%")
            ->orWhere('deskripsi', 'LIKE', "%{$query}%")
            ->select('id', 'judul', 'thumbnail', 'guru_id')
            ->with('guru:id,name')
            ->where('status', 'published')
            ->limit(5)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->judul,
                    'thumbnail' => $course->thumbnail,
                    'teacher' => $course->guru->name ?? 'Unknown',
                    'type' => 'course',
                    'url' => route('courses.show', $course->id),
                ];
            });

        // Search Lessons
        $lessons = Lesson::where('judul', 'LIKE', "%{$query}%")
            ->orWhere('konten_teks', 'LIKE', "%{$query}%")
            ->select('id', 'judul', 'course_id')
            ->with('course:id,judul')
            ->limit(5)
            ->get()
            ->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->judul,
                    'course' => $lesson->course->judul ?? 'Unknown',
                    'type' => 'lesson',
                    'url' => route('lessons.show', $lesson->id),
                ];
            });

        return response()->json([
            'courses' => $courses,
            'lessons' => $lessons,
        ]);
    }
}
