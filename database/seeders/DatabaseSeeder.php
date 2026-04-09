<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil semua seeder dengan urutan yang benar
        $this->call([
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            CoursesTableSeeder::class,
            LessonsTableSeeder::class,
            QuizzesTableSeeder::class,
            QuestionsTableSeeder::class,
            EnrollmentsTableSeeder::class, // Opsional, comment jika tidak ingin data enroll contoh
        ]);

        $this->command->info('🎉 All seeders completed successfully!');
        $this->command->info('📝 Login credentials:');
        $this->command->info('Admin: admin@lms.com / password');
        $this->command->info('Guru: guru1@lms.com / password');
        $this->command->info('Siswa: siswa1@lms.com / password');
    }
}
