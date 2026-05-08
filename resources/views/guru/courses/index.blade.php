@extends('layouts.app')

@section('title', 'Kursus Saya')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-chalkboard mr-3 text-blue-600"></i>Kursus Saya
            </h1>
            <p class="text-sm text-gray-500 mt-1">Kelola kursus yang Anda ajarkan</p>
        </div>
        <a href="{{ route('courses.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Kursus Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-blue-100">Total Kursus</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalCourses }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-emerald-100">Published</p>
                    <p class="text-3xl font-bold mt-1">{{ $publishedCourses }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-600 to-gray-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-100">Draft</p>
                    <p class="text-3xl font-bold mt-1">{{ $draftCourses }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-pencil-alt text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-600 to-purple-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-purple-100">Total Siswa</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalStudents }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari kursus</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Judul kursus...">
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                <select name="category" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[140px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Urutkan</label>
                <select name="sort" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul A-Z</option>
                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul Z-A</option>
                    <option value="most_enrolled" {{ request('sort') == 'most_enrolled' ? 'selected' : '' }}>Terpopuler</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
            <div>
                <a href="{{ route('courses.index') }}" class="inline-flex px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-all">
                    <i class="fas fa-sync mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Courses Grid -->
    @if($courses->count() > 0)
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($courses as $course)
        <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <!-- Thumbnail -->
            <div class="relative h-48 overflow-hidden">
                @if($course->thumbnail)
                    <img src="{{ Storage::url($course->thumbnail) }}"
                         alt="{{ $course->judul }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-5xl text-white/50"></i>
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg shadow-sm
                        {{ $course->status == 'published' ? 'bg-emerald-500 text-white' : 'bg-gray-500 text-white' }}">
                        {{ $course->status == 'published' ? 'Published' : 'Draft' }}
                    </span>
                </div>

                <!-- Price Badge -->
                <div class="absolute bottom-3 left-3">
                    @if($course->harga > 0)
                        <span class="inline-flex px-2 py-1 text-xs font-bold rounded-lg bg-white/90 backdrop-blur-sm text-blue-600 shadow-sm">
                            Rp {{ number_format($course->harga, 0, ',', '.') }}
                        </span>
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-bold rounded-lg bg-emerald-500 text-white shadow-sm">
                            Gratis
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="p-5">
                <!-- Category -->
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-tag text-xs text-gray-400"></i>
                    <span class="text-xs text-gray-500">{{ $course->kategori->nama ?? 'Uncategorized' }}</span>
                </div>

                <!-- Title -->
                <h3 class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors line-clamp-1">
                    {{ $course->judul }}
                </h3>

                <!-- Description -->
                <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                    {{ Str::limit($course->deskripsi, 80) }}
                </p>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-gray-100">
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-1">
                            <i class="fas fa-book text-xs text-blue-500"></i>
                            <span class="text-sm font-semibold text-gray-700">{{ $course->lessons_count ?? 0 }}</span>
                        </div>
                        <p class="text-xs text-gray-400">Lesson</p>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-1">
                            <i class="fas fa-users text-xs text-emerald-500"></i>
                            <span class="text-sm font-semibold text-gray-700">{{ $course->enrollments_count ?? 0 }}</span>
                        </div>
                        <p class="text-xs text-gray-400">Siswa</p>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-1">
                            <i class="fas fa-star text-xs text-amber-500"></i>
                            <span class="text-sm font-semibold text-gray-700">{{ $course->rating ?? 0 }}</span>
                        </div>
                        <p class="text-xs text-gray-400">Rating</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
                <div class="flex gap-2">
                    <a href="{{ route('courses.show', $course) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                        <i class="fas fa-eye text-xs"></i> Lihat
                    </a>
                    <a href="{{ route('courses.edit', $course) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 text-xs font-medium text-amber-600 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors">
                        <i class="fas fa-edit text-xs"></i> Edit
                    </a>
                    <a href="{{ route('lessons.create', $course) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">
                        <i class="fas fa-plus text-xs"></i> Lesson
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $courses->withQueryString()->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <h3 class="mt-4 text-xl font-semibold text-gray-700">Belum Ada Kursus</h3>
        <p class="text-gray-400 mt-2">Mulai buat kursus pertama Anda dan bagikan ilmu Anda!</p>
        <a href="{{ route('courses.create') }}"
           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Kursus Pertama
        </a>
    </div>
    @endif
</div>

@push('styles')
<style>
    /* Line clamp untuk truncate text */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Custom pagination styling */
    .pagination {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    .pagination .page-item .page-link {
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        color: #4b5563;
        background-color: white;
        border: 1px solid #e5e7eb;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .pagination .page-item.active .page-link {
        background-color: #2563eb;
        border-color: #2563eb;
        color: white;
    }
    .pagination .page-item .page-link:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }
</style>
@endpush
@endsection
