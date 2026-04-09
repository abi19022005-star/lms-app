@extends('layouts.app')

@section('title', 'Siswa Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-user-graduate mr-3 text-blue-600"></i>Siswa Dashboard
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Selamat belajar, {{ auth()->user()->name }} 🚀
        </p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="bg-blue-600 text-white rounded-2xl p-5 shadow-sm">
            <p class="text-sm opacity-80">Courses Enrolled</p>
            <h2 class="text-2xl font-bold">{{ $totalCoursesEnrolled ?? 0 }}</h2>
        </div>

        <div class="bg-emerald-600 text-white rounded-2xl p-5 shadow-sm">
            <p class="text-sm opacity-80">Completed</p>
            <h2 class="text-2xl font-bold">{{ $completedCourses ?? 0 }}</h2>
        </div>

        <div class="bg-cyan-600 text-white rounded-2xl p-5 shadow-sm">
            <p class="text-sm opacity-80">Average Progress</p>
            <h2 class="text-2xl font-bold">{{ number_format($averageProgress ?? 0, 0) }}%</h2>
        </div>

    </div>

    <!-- My Courses -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h6 class="text-sm font-semibold text-gray-700 mb-4">
            <i class="fas fa-graduation-cap mr-2"></i>My Courses
        </h6>

        @forelse($learningProgress ?? [] as $progress)
        <div class="mb-5">

            <!-- Header -->
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h5 class="font-semibold text-gray-800">
                        {{ $progress['course']->judul }}
                    </h5>
                    <p class="text-xs text-gray-400">
                        <i class="fas fa-user mr-1"></i>{{ $progress['course']->guru->name }}
                    </p>
                </div>

                <span class="px-2 py-1 text-xs rounded-lg
                    {{ $progress['status'] == 'completed'
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-amber-100 text-amber-700' }}">
                    {{ ucfirst($progress['status']) }}
                </span>
            </div>

            <!-- Progress -->
            <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full
                    {{ $progress['status'] == 'completed' ? 'bg-emerald-500' : 'bg-blue-500' }}"
                    style="width: {{ $progress['progress'] }}%">
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center">
                <p class="text-xs text-gray-400">
                    Last activity: {{ $progress['last_activity']->diffForHumans() }}
                </p>

                <a href="{{ route('courses.show', $progress['course']) }}"
                   class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    {{ $progress['status'] == 'completed' ? 'Review' : 'Continue' }}
                </a>
            </div>

        </div>

        @if(!$loop->last)
        <div class="border-t mb-5"></div>
        @endif

        @empty
        <div class="text-center py-10">
            <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Belum ada kursus</p>
            <a href="{{ route('courses.index') }}"
               class="mt-3 inline-flex px-4 py-2 bg-blue-600 text-white rounded-xl text-sm">
                Cari Kursus
            </a>
        </div>
        @endforelse
    </div>

    <!-- Certificates & Quiz -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <!-- Certificates -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h6 class="text-sm font-semibold text-gray-700 mb-4">
                <i class="fas fa-certificate mr-2 text-amber-500"></i>Certificates
            </h6>

            @forelse($certificates ?? [] as $certificate)
            <div class="flex justify-between items-center mb-3">
                <div>
                    <p class="text-sm font-medium">{{ $certificate->course->judul }}</p>
                    <p class="text-xs text-gray-400">
                        {{ $certificate->issued_at->format('d M Y') }}
                    </p>
                </div>

                <a href="{{ route('certificates.download', $certificate) }}"
                   class="px-3 py-1.5 text-xs bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                    PDF
                </a>
            </div>

            @if(!$loop->last)
            <div class="border-t my-3"></div>
            @endif

            @empty
            <div class="text-center py-6 text-gray-400">
                Belum ada sertifikat
            </div>
            @endforelse
        </div>

        <!-- Quiz -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h6 class="text-sm font-semibold text-gray-700 mb-4">
                <i class="fas fa-history mr-2"></i>Quiz History
            </h6>

            @forelse($quizAttempts ?? [] as $attempt)
            <div class="flex justify-between items-center mb-3">
                <div>
                    <p class="text-sm font-medium">{{ $attempt->quiz->judul }}</p>
                    <p class="text-xs text-gray-400">{{ $attempt->quiz->course->judul }}</p>
                </div>

                <div class="text-right">
                    <span class="px-2 py-1 text-xs rounded-lg
                        {{ $attempt->total_score >= $attempt->quiz->passing_score
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-red-100 text-red-600' }}">
                        {{ round($attempt->total_score, 2) }}%
                    </span>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $attempt->submitted_at->format('d M Y') }}
                    </p>
                </div>
            </div>

            @if(!$loop->last)
            <div class="border-t my-3"></div>
            @endif

            @empty
            <div class="text-center py-6 text-gray-400">
                Belum ada riwayat kuis
            </div>
            @endforelse
        </div>

    </div>

    <!-- Recommended -->
    @if(isset($recommendedCourses) && $recommendedCourses->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h6 class="text-sm font-semibold text-gray-700 mb-4">
            <i class="fas fa-star mr-2 text-yellow-500"></i>Recommended
        </h6>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($recommendedCourses as $course)
            <div class="border rounded-xl p-4 hover:shadow-sm transition">
                <p class="font-medium text-sm">
                    {{ Str::limit($course->judul, 40) }}
                </p>

                <p class="text-xs text-gray-400 mt-1">
                    {{ Str::limit($course->deskripsi, 80) }}
                </p>

                <div class="flex justify-between items-center mt-3">
                    <span class="text-xs px-2 py-1 rounded-lg
                        {{ $course->harga > 0
                            ? 'bg-amber-100 text-amber-700'
                            : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $course->harga > 0
                            ? 'Rp ' . number_format($course->harga, 0, ',', '.')
                            : 'Gratis' }}
                    </span>

                    <a href="{{ route('courses.show', $course) }}"
                       class="text-xs text-blue-600 hover:underline">
                        Lihat →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
