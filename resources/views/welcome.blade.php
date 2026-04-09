<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LMS') }}</title>

    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Hero Section -->
    <section class="relative min-h-screen bg-gradient-to-br from-blue-600 via-blue-500 to-purple-600 flex items-center overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 w-full">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="text-white">
                        <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                            Learn Anytime,<br> Anywhere
                        </h1>
                        <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                            Platform e-learning modern yang membantu Anda mengembangkan skill dan karir melalui kursus berkualitas dari para ahli.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-3 bg-white text-blue-600 font-semibold rounded-full hover:bg-gray-50 transition transform hover:-translate-y-1 shadow-lg">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 bg-white text-blue-600 font-semibold rounded-full hover:bg-gray-50 transition transform hover:-translate-y-1 shadow-lg">
                                    <i class="fas fa-user-plus mr-2"></i> Mulai Belajar
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3 bg-blue-700 text-white font-semibold rounded-full hover:bg-blue-800 transition transform hover:-translate-y-1 border-2 border-white/20">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Right Illustration -->
                    <div class="hidden lg:flex justify-center">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-2xl blur-2xl"></div>
                            <img src="https://via.placeholder.com/500x400?text=Learning+Illustration" alt="Learning" class="relative rounded-2xl shadow-2xl w-full">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Mengapa Memilih Kami?
                </h2>
                <p class="text-xl text-gray-600">
                    Kami menyediakan platform belajar terbaik untuk masa depan Anda
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-lg hover:-translate-y-2 transition duration-300">
                    <div class="mb-6 text-blue-600 text-5xl">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Instruktur Profesional</h3>
                    <p class="text-gray-600">
                        Belajar dari para ahli industri dengan pengalaman bertahun-tahun.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-lg hover:-translate-y-2 transition duration-300">
                    <div class="mb-6 text-emerald-600 text-5xl">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Sertifikat Resmi</h3>
                    <p class="text-gray-600">
                        Dapatkan sertifikat yang diakui setelah menyelesaikan kursus.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-lg hover:-translate-y-2 transition duration-300">
                    <div class="mb-6 text-purple-600 text-5xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Akses Seumur Hidup</h3>
                    <p class="text-gray-600">
                        Akses materi kursus kapan saja, di mana saja, tanpa batas waktu.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Stat 1 -->
                <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/10 rounded-2xl p-8 border border-blue-200/50 text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">500+</div>
                    <p class="text-gray-600 font-medium">Siswa Aktif</p>
                </div>

                <!-- Stat 2 -->
                <div class="bg-gradient-to-br from-emerald-500/10 to-emerald-600/10 rounded-2xl p-8 border border-emerald-200/50 text-center">
                    <div class="text-4xl font-bold text-emerald-600 mb-2">50+</div>
                    <p class="text-gray-600 font-medium">Kursus Tersedia</p>
                </div>

                <!-- Stat 3 -->
                <div class="bg-gradient-to-br from-purple-500/10 to-purple-600/10 rounded-2xl p-8 border border-purple-200/50 text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">20+</div>
                    <p class="text-gray-600 font-medium">Instruktur Ahli</p>
                </div>

                <!-- Stat 4 -->
                <div class="bg-gradient-to-br from-amber-500/10 to-amber-600/10 rounded-2xl p-8 border border-amber-200/50 text-center">
                    <div class="text-4xl font-bold text-amber-600 mb-2">100%</div>
                    <p class="text-gray-600 font-medium">Kepuasan Siswa</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-blue-600 to-purple-600 overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute inset-0">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- CTA Content -->
        <div class="relative z-10 max-w-4xl mx-auto text-center">
            <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                Siap Memulai Perjalanan Belajar Anda?
            </h2>
            <p class="text-xl text-blue-100 mb-10">
                Bergabunglah dengan ribuan siswa lainnya dan tingkatkan skill Anda sekarang!
            </p>
            @guest
                <a href="{{ route('register') }}" class="inline-flex items-center px-10 py-4 bg-white text-blue-600 font-semibold rounded-full hover:bg-gray-50 transition transform hover:-translate-y-1 shadow-xl text-lg">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <!-- Brand -->
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ setting('app_name', config('app.name')) }}</h3>
                    <p class="text-gray-400 mb-6">
                        Platform e-learning terpercaya untuk pengembangan diri dan karir.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-400 hover:text-white transition text-lg">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition text-lg">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition text-lg">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition text-lg">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Tautan Cepat</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Kontak</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Karir</a></li>
                    </ul>
                </div>

                <!-- Help -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Bantuan</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Kontak</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope w-5"></i> info@lms.com
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone w-5"></i> +62 123 4567 890
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-800"></div>

            <!-- Bottom -->
            <div class="mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ setting('app_name', config('app.name')) }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
