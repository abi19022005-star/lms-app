<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'bio' => 'Administrator sistem e-learning',
            'foto' => null,
        ]);

        // Guru 1
        User::create([
            'name' => 'Budi Santoso, S.Kom',
            'email' => 'guru1@lms.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'bio' => 'Guru programming dengan pengalaman 10 tahun di industri software development',
            'foto' => null,
        ]);

        // Guru 2
        User::create([
            'name' => 'Siti Aminah, M.Kom',
            'email' => 'guru2@lms.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'bio' => 'Ahli database dan backend development',
            'foto' => null,
        ]);

        // Guru 3
        User::create([
            'name' => 'Dr. Rizki Fauzi',
            'email' => 'guru3@lms.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'bio' => 'Frontend developer dan UI/UX expert',
            'foto' => null,
        ]);

        // Siswa 1
        User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'siswa1@lms.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'bio' => 'Mahasiswa semester akhir Teknik Informatika',
            'foto' => null,
        ]);

        // Siswa 2
        User::create([
            'name' => 'Dewi Putri',
            'email' => 'siswa2@lms.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'bio' => 'Fresh graduate yang ingin mendalami web development',
            'foto' => null,
        ]);

        // Siswa 3
        User::create([
            'name' => 'Eko Prasetyo',
            'email' => 'siswa3@lms.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'bio' => 'Profesional yang ingin beralih karir ke programming',
            'foto' => null,
        ]);

        // Siswa 4
        User::create([
            'name' => 'Rina Marlina',
            'email' => 'siswa4@lms.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'bio' => 'Freelancer web developer',
            'foto' => null,
        ]);

        // Siswa 5
        User::create([
            'name' => 'Bayu Saputra',
            'email' => 'siswa5@lms.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'bio' => 'Mahasiswa yang tertarik dengan Laravel',
            'foto' => null,
        ]);

        $this->command->info('✅ Users seeded: 1 admin, 3 guru, 5 siswa');
    }
}
