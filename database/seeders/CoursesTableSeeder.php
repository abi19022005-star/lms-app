<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID guru dengan cara yang lebih aman
        $guru1 = User::where('email', 'guru1@lms.com')->first();
        $guru2 = User::where('email', 'guru2@lms.com')->first();
        $guru3 = User::where('email', 'guru3@lms.com')->first();

        // Cek jika guru tidak ditemukan, tampilkan pesan error
        if (!$guru1 || !$guru2 || !$guru3) {
            $this->command->error('❌ Guru tidak ditemukan! Pastikan UsersTableSeeder sudah dijalankan dengan benar.');
            $this->command->info('📝 Email guru yang seharusnya ada:');
            $this->command->info('   - guru1@lms.com');
            $this->command->info('   - guru2@lms.com');
            $this->command->info('   - guru3@lms.com');
            return;
        }

        // Kursus dari Guru 1
        Course::create([
            'guru_id' => $guru1->id,
            'kategori_id' => 1, // Web Development
            'judul' => 'Laravel 11 untuk Pemula',
            'deskripsi' => 'Belajar Laravel dari dasar hingga mahir. Kursus ini mencakup routing, controller, blade, eloquent, authentication, dan deployment.',
            'thumbnail' => null,
            'harga' => 0, // Gratis
            'status' => 'published',
        ]);

        Course::create([
            'guru_id' => $guru1->id,
            'kategori_id' => 1,
            'judul' => 'Mastering Vue.js 3',
            'deskripsi' => 'Pelajari Vue.js 3 dari dasar hingga advanced. Composition API, Pinia, Vue Router, dan integrasi dengan backend.',
            'thumbnail' => null,
            'harga' => 150000,
            'status' => 'published',
        ]);

        // Kursus dari Guru 2
        Course::create([
            'guru_id' => $guru2->id,
            'kategori_id' => 1,
            'judul' => 'RESTful API dengan Laravel',
            'deskripsi' => 'Membuat RESTful API profesional menggunakan Laravel. Termasuk authentication, rate limiting, versioning, dan dokumentasi dengan Swagger.',
            'thumbnail' => null,
            'harga' => 200000,
            'status' => 'published',
        ]);

        Course::create([
            'guru_id' => $guru2->id,
            'kategori_id' => 3, // Data Science
            'judul' => 'Python untuk Data Science',
            'deskripsi' => 'Belajar Python, Pandas, NumPy, Matplotlib, dan dasar-dasar machine learning.',
            'thumbnail' => null,
            'harga' => 250000,
            'status' => 'draft', // Masih draft
        ]);

        // Kursus dari Guru 3
        Course::create([
            'guru_id' => $guru3->id,
            'kategori_id' => 4, // UI/UX Design
            'judul' => 'Figma Masterclass',
            'deskripsi' => 'Desain UI/UX profesional dengan Figma. Dari wireframe hingga prototype interaktif.',
            'thumbnail' => null,
            'harga' => 0,
            'status' => 'published',
        ]);

        Course::create([
            'guru_id' => $guru3->id,
            'kategori_id' => 1,
            'judul' => 'Tailwind CSS dari Nol',
            'deskripsi' => 'Belajar Tailwind CSS untuk mempercepat development website modern.',
            'thumbnail' => null,
            'harga' => 0,
            'status' => 'published',
        ]);

        $this->command->info('✅ Courses seeded: 6 courses');
    }
}
