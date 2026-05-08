@extends('layouts.app')

@section('title', 'Kursus Saya')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                Kursus Saya
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Kursus yang sedang Anda ikuti
            </p>
        </div>

        <a href="{{ route('courses.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-xl hover:bg-gray-200 transition">
            <i class="fas fa-plus"></i>
            <span>Cari Kursus Lain</span>
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('siswa.courses.my') }}" class="flex flex-wrap gap-3 items-end">

            <div class="flex-1 min-w-[180px]">
                <label class="text-xs text-gray-500 font-medium">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                       placeholder="Cari kursus...">
            </div>

            <div>
                <label class="text-xs text-gray-500 font-medium">Status</label>
                <select name="status"
                        class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Sedang Belajar</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
            </div>

            @if(request('search') || request('status'))
                <a href="{{ route('courses.my') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-xl hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            @endif

        </form>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($enrollments as $enrollment)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">

            <!-- Thumbnail -->
            <div class="relative overflow-hidden">
                @if($enrollment->course->thumbnail)
                    <img src="{{ Storage::url($enrollment->course->thumbnail) }}"
                         class="w-full h-44 object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-400">
                        <i class="fas fa-image text-3xl"></i>
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    @if($enrollment->status == 'completed')
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-500 text-white text-xs font-semibold rounded-full">
                            <i class="fas fa-check-circle"></i> Selesai
                        </span>
                    @elseif($enrollment->status == 'in_progress')
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full">
                            <i class="fas fa-spinner"></i> Belajar
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-500 text-white text-xs font-semibold rounded-full">
                            <i class="fas fa-clock"></i> Mulai
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 space-y-3 flex flex-col h-full">

                <h5 class="text-sm font-semibold text-gray-800 line-clamp-2">
                    {{ $enrollment->course->judul }}
                </h5>

                <p class="text-xs text-gray-400">
                    <i class="fas fa-user mr-1"></i> {{ $enrollment->course->guru->name ?? 'Unknown' }} <br>
                    <i class="fas fa-tag mr-1"></i> {{ $enrollment->course->kategori->nama ?? '-' }}
                </p>

                <p class="text-sm text-gray-600 line-clamp-2">
                    {{ Str::limit($enrollment->course->deskripsi, 90) }}
                </p>

                <!-- Progress Bar -->
                <div class="space-y-1 mt-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-600 font-medium">Progress</span>
                        <span class="text-blue-600 font-semibold">{{ $enrollment->progress ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300"
                             style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                    </div>
                </div>

                <!-- Info -->
                <div class="flex justify-between items-center text-xs pt-2">
                    <span class="px-2 py-1 rounded-lg {{ $enrollment->course->harga > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $enrollment->course->harga > 0 ? 'Rp ' . number_format($enrollment->course->harga, 0, ',', '.') : 'Gratis' }}
                    </span>

                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg">
                        {{ $enrollment->course->lessons->count() }} Lesson
                    </span>
                </div>

                <!-- Enrolled Date -->
                <p class="text-xs text-gray-400 pt-1">
                    <i class="fas fa-calendar-alt mr-1"></i> Terdaftar {{ $enrollment->enrolled_at->format('d/m/Y') }}
                </p>

                <!-- Button -->
                <a href="{{ route('courses.show', $enrollment->course) }}"
                   class="mt-auto block text-center px-4 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700 transition font-medium">
                    @if($enrollment->status == 'completed')
                        <i class="fas fa-eye mr-1"></i> Lihat Lagi
                    @else
                        <i class="fas fa-play-circle mr-1"></i> Lanjutkan
                    @endif
                </a>

            </div>
        </div>

        @empty
        <div class="col-span-full text-center py-16">
            <div class="mb-4">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-4 block"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum ada kursus yang diambil</h3>
            <p class="text-gray-500 mb-6">Mulai belajar dengan memilih kursus dari katalog kami</p>
            <a href="{{ route('courses.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium">
                <i class="fas fa-search"></i>
                Jelajahi Kursus
            </a>
        </div>
        @endforelse

    </div>

    <!-- Pagination -->
    @if($enrollments->hasPages())
    <div class="pt-4">
        {{ $enrollments->withQueryString()->links() }}
    </div>
    @endif

</div>
@endsection
