<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;

class EnrollmentsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil siswa dan kursus
        $siswa1 = User::where('email', 'siswa1@example.com')->first();
        $siswa2 = User::where('email', 'siswa2@example.com')->first();
        $siswa3 = User::where('email', 'siswa3@example.com')->first();

        $laravelCourse = Course::where('judul', 'Laravel 11 untuk Pemula')->first();
        $vueCourse = Course::where('judul', 'Mastering Vue.js 3')->first();
        $apiCourse = Course::where('judul', 'RESTful API dengan Laravel')->first();
        $tailwindCourse = Course::where('judul', 'Tailwind CSS dari Nol')->first();

        // Siswa 1 enroll ke beberapa kursus
        if ($siswa1 && $laravelCourse) {
            Enrollment::create([
                'user_id' => $siswa1->id,
                'course_id' => $laravelCourse->id,
                'status' => 'active',
                'progress' => 45,
                'enrolled_at' => now(),
            ]);
        }

        if ($siswa1 && $vueCourse) {
            Enrollment::create([
                'user_id' => $siswa1->id,
                'course_id' => $vueCourse->id,
                'status' => 'active',
                'progress' => 20,
                'enrolled_at' => now(),
            ]);
        }

        // Siswa 2 enroll
        if ($siswa2 && $laravelCourse) {
            Enrollment::create([
                'user_id' => $siswa2->id,
                'course_id' => $laravelCourse->id,
                'status' => 'completed',
                'progress' => 100,
                'enrolled_at' => now()->subDays(10),
                'completed_at' => now()->subDays(2),
            ]);
        }

        if ($siswa2 && $apiCourse) {
            Enrollment::create([
                'user_id' => $siswa2->id,
                'course_id' => $apiCourse->id,
                'status' => 'active',
                'progress' => 60,
                'enrolled_at' => now()->subDays(5),
            ]);
        }

        // Siswa 3 enroll
        if ($siswa3 && $tailwindCourse) {
            Enrollment::create([
                'user_id' => $siswa3->id,
                'course_id' => $tailwindCourse->id,
                'status' => 'active',
                'progress' => 0,
                'enrolled_at' => now(),
            ]);
        }

        $this->command->info('✅ Enrollments seeded: ' . Enrollment::count() . ' enrollments');
    }
}
