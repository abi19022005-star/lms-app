<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Soal untuk kuis Laravel
        $laravelQuiz = Quiz::where('judul', 'Kuis Akhir Laravel 11')->first();

        if ($laravelQuiz) {
            // Multiple Choice Questions
            $mcqs = [
                [
                    'pertanyaan' => 'Apa fungsi dari routing di Laravel?',
                    'opsi' => json_encode([
                        'A' => 'Menghubungkan URL ke controller',
                        'B' => 'Mengatur database',
                        'C' => 'Membuat view',
                        'D' => 'Mengelola session'
                    ]),
                    'jawaban_benar' => 'Menghubungkan URL ke controller',
                ],
                [
                    'pertanyaan' => 'Blade template menggunakan ekstensi file apa?',
                    'opsi' => json_encode([
                        'A' => '.php',
                        'B' => '.html',
                        'C' => '.blade.php',
                        'D' => '.template.php'
                    ]),
                    'jawaban_benar' => '.blade.php',
                ],
                [
                    'pertanyaan' => 'Artisan adalah...',
                    'opsi' => json_encode([
                        'A' => 'Command line tool Laravel',
                        'B' => 'Database ORM',
                        'C' => 'Template engine',
                        'D' => 'Package manager'
                    ]),
                    'jawaban_benar' => 'Command line tool Laravel',
                ],
                [
                    'pertanyaan' => 'Eloquent adalah...',
                    'opsi' => json_encode([
                        'A' => 'Template engine',
                        'B' => 'ORM untuk database',
                        'C' => 'Library untuk testing',
                        'D' => 'Package manager'
                    ]),
                    'jawaban_benar' => 'ORM untuk database',
                ],
                [
                    'pertanyaan' => 'Perintah untuk membuat migration di Laravel?',
                    'opsi' => json_encode([
                        'A' => 'php artisan make:migration',
                        'B' => 'php artisan create:migration',
                        'C' => 'php artisan migrate:make',
                        'D' => 'php artisan migration:create'
                    ]),
                    'jawaban_benar' => 'php artisan make:migration',
                ],
            ];

            foreach ($mcqs as $mcq) {
                Question::create(array_merge($mcq, [
                    'quiz_id' => $laravelQuiz->id,
                    'tipe' => 'multiple_choice',
                ]));
            }

            // Essay Questions
            $essays = [
                [
                    'pertanyaan' => 'Jelaskan kelebihan menggunakan framework Laravel dibandingkan PHP native! (Minimal 3 poin)',
                ],
                [
                    'pertanyaan' => 'Bagaimana cara kerja MVC (Model-View-Controller) di Laravel? Jelaskan alur kerjanya!',
                ],
                [
                    'pertanyaan' => 'Apa yang dimaksud dengan Eloquent ORM dan bagaimana cara menggunakannya? Berikan contoh kode!',
                ],
            ];

            foreach ($essays as $essay) {
                Question::create([
                    'quiz_id' => $laravelQuiz->id,
                    'tipe' => 'essay',
                    'pertanyaan' => $essay['pertanyaan'],
                    'opsi' => null,
                    'jawaban_benar' => null,
                ]);
            }
        }

        // Soal untuk kuis Vue.js
        $vueQuiz = Quiz::where('judul', 'Vue.js 3 Mastery Test')->first();
        if ($vueQuiz) {
            $vueMcqs = [
                [
                    'pertanyaan' => 'Apa perbedaan utama antara Composition API dan Options API?',
                    'opsi' => json_encode([
                        'A' => 'Composition API lebih sulit digunakan',
                        'B' => 'Composition API memungkinkan code organization yang lebih baik',
                        'C' => 'Options API lebih baru',
                        'D' => 'Tidak ada perbedaan'
                    ]),
                    'jawaban_benar' => 'Composition API memungkinkan code organization yang lebih baik',
                ],
                [
                    'pertanyaan' => 'Pinia digunakan untuk...',
                    'opsi' => json_encode([
                        'A' => 'Routing',
                        'B' => 'State Management',
                        'C' => 'Testing',
                        'D' => 'Build tools'
                    ]),
                    'jawaban_benar' => 'State Management',
                ],
            ];

            foreach ($vueMcqs as $mcq) {
                Question::create(array_merge($mcq, [
                    'quiz_id' => $vueQuiz->id,
                    'tipe' => 'multiple_choice',
                ]));
            }

            Question::create([
                'quiz_id' => $vueQuiz->id,
                'tipe' => 'essay',
                'pertanyaan' => 'Jelaskan keuntungan menggunakan Vue.js untuk frontend development!',
            ]);
        }

        $this->command->info('✅ Questions seeded: ' . Question::count() . ' questions');
    }
}
