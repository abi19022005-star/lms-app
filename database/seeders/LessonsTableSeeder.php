<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil kursus Laravel untuk Pemula
        $laravelCourse = Course::where('judul', 'Laravel 11 untuk Pemula')->first();

        // Lessons untuk kursus Laravel
        $laravelLessons = [
            [
                'judul' => 'Pengenalan Laravel',
                'tipe' => 'teks',
                'konten_teks' => '<h3>Apa itu Laravel?</h3><p>Laravel adalah framework PHP yang powerful untuk web development...</p><h3>Kenapa Laravel?</h3><ul><li>Elegant syntax</li><li>MVC architecture</li><li>Built-in authentication</li><li>Eloquent ORM</li></ul>',
                'order' => 1,
            ],
            [
                'judul' => 'Instalasi dan Konfigurasi',
                'tipe' => 'video',
                'url_video' => 'https://www.youtube.com/embed/example1',
                'order' => 2,
            ],
            [
                'judul' => 'Routing Dasar',
                'tipe' => 'teks',
                'konten_teks' => '<h3>Basic Routing</h3><p>Routing digunakan untuk mendefinisikan URL endpoint aplikasi...</p><pre><code>Route::get(\'/home\', function() { return view(\'home\'); });</code></pre>',
                'order' => 3,
            ],
            [
                'judul' => 'Controller',
                'tipe' => 'teks',
                'konten_teks' => '<h3>Membuat Controller</h3><p>Controller menangani logika request...</p>',
                'order' => 4,
            ],
            [
                'judul' => 'Blade Template Engine',
                'tipe' => 'teks',
                'konten_teks' => '<h3>Blade Templates</h3><p>Blade adalah template engine Laravel...</p>',
                'order' => 5,
            ],
            [
                'judul' => 'Eloquent ORM',
                'tipe' => 'teks',
                'konten_teks' => '<h3>Database dengan Eloquent</h3><p>Eloquent memudahkan interaksi database...</p>',
                'order' => 6,
            ],
            [
                'judul' => 'Authentication & Authorization',
                'tipe' => 'video',
                'url_video' => 'https://www.youtube.com/embed/example2',
                'order' => 7,
            ],
            [
                'judul' => 'Deployment ke Production',
                'tipe' => 'teks',
                'konten_teks' => '<h3>Deploy Laravel</h3><p>Langkah-langkah deploy aplikasi Laravel ke hosting...</p>',
                'order' => 8,
            ],
        ];

        foreach ($laravelLessons as $lesson) {
            $lesson['course_id'] = $laravelCourse->id;
            Lesson::create($lesson);
        }

        // Lessons untuk kursus Vue.js
        $vueCourse = Course::where('judul', 'Mastering Vue.js 3')->first();
        if ($vueCourse) {
            $vueLessons = [
                [
                    'judul' => 'Introduction to Vue.js 3',
                    'tipe' => 'video',
                    'url_video' => 'https://www.youtube.com/embed/vue1',
                    'order' => 1,
                ],
                [
                    'judul' => 'Composition API',
                    'tipe' => 'teks',
                    'konten_teks' => '<h3>Composition API vs Options API</h3><p>Perbandingan dan kapan menggunakannya...</p>',
                    'order' => 2,
                ],
                [
                    'judul' => 'Vue Router',
                    'tipe' => 'teks',
                    'konten_teks' => '<h3>Routing dengan Vue Router</h3><p>Implementasi routing di Vue.js...</p>',
                    'order' => 3,
                ],
                [
                    'judul' => 'State Management with Pinia',
                    'tipe' => 'video',
                    'url_video' => 'https://www.youtube.com/embed/pinia1',
                    'order' => 4,
                ],
            ];

            foreach ($vueLessons as $lesson) {
                $lesson['course_id'] = $vueCourse->id;
                Lesson::create($lesson);
            }
        }

        // Lessons untuk kursus RESTful API
        $apiCourse = Course::where('judul', 'RESTful API dengan Laravel')->first();
        if ($apiCourse) {
            $apiLessons = [
                [
                    'judul' => 'Pengenalan REST API',
                    'tipe' => 'teks',
                    'konten_teks' => '<h3>Apa itu REST API?</h3><p>Konsep dasar RESTful API...</p>',
                    'order' => 1,
                ],
                [
                    'judul' => 'Membuat API Resources',
                    'tipe' => 'teks',
                    'konten_teks' => '<h3>API Resources di Laravel</h3><p>Transformasi data dengan API Resources...</p>',
                    'order' => 2,
                ],
                [
                    'judul' => 'API Authentication dengan Sanctum',
                    'tipe' => 'video',
                    'url_video' => 'https://www.youtube.com/embed/sanctum1',
                    'order' => 3,
                ],
            ];

            foreach ($apiLessons as $lesson) {
                $lesson['course_id'] = $apiCourse->id;
                Lesson::create($lesson);
            }
        }

        $this->command->info('✅ Lessons seeded for multiple courses');
    }
}
