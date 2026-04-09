<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuizzesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Kuis untuk kursus Laravel
        $laravelCourse = Course::where('judul', 'Laravel 11 untuk Pemula')->first();
        Quiz::create([
            'course_id' => $laravelCourse->id,
            'judul' => 'Kuis Akhir Laravel 11',
            'passing_score' => 70,
        ]);

        // Kuis untuk kursus Vue.js
        $vueCourse = Course::where('judul', 'Mastering Vue.js 3')->first();
        if ($vueCourse) {
            Quiz::create([
                'course_id' => $vueCourse->id,
                'judul' => 'Vue.js 3 Mastery Test',
                'passing_score' => 75,
            ]);
        }

        // Kuis untuk kursus RESTful API
        $apiCourse = Course::where('judul', 'RESTful API dengan Laravel')->first();
        if ($apiCourse) {
            Quiz::create([
                'course_id' => $apiCourse->id,
                'judul' => 'API Development Quiz',
                'passing_score' => 80,
            ]);
        }

        $this->command->info('✅ Quizzes seeded: 3 quizzes');
    }
}
