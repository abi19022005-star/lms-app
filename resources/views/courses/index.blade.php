@extends('layouts.app')

@section('title', 'Daftar Kursus')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                📚 Daftar Kursus
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Temukan kursus terbaik untuk meningkatkan skill Anda
            </p>
        </div>

        @auth
            @if(auth()->user()->isGuru() || auth()->user()->isAdmin())
                <a href="{{ route('courses.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700">
                    <i class="fas fa-plus"></i>
                     Buat Kursus
                </a>
            @endif
        @endauth
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('courses.index') }}" class="flex flex-wrap gap-3 items-end">

            <div class="flex-1 min-w-[180px]">
                <label class="text-xs text-gray-500">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                       placeholder="Cari kursus...">
            </div>

            <div>
                <label class="text-xs text-gray-500">Kategori</label>
                <select name="category"
                        class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500">Harga</label>
                <select name="price"
                        class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="free" {{ request('price') == 'free' ? 'selected' : '' }}>Gratis</option>
                    <option value="paid" {{ request('price') == 'paid' ? 'selected' : '' }}>Berbayar</option>
                </select>
            </div>

            <div>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
            </div>

        </form>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($courses as $course)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

            <!-- Thumbnail -->
            @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}"
                         alt="{{ $course->judul }}"
                         class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">

            @else
                <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-400">
                    <i class="fas fa-image text-3xl"></i>
                </div>
            @endif

            <!-- Content -->
            <div class="p-4 space-y-3">

                <h5 class="text-sm font-semibold text-gray-800">
                    {{ Str::limit($course->judul, 50) }}
                </h5>

                <p class="text-xs text-gray-400">
                    <i class="fas fa-user mr-1"></i> {{ $course->guru->name ?? 'Unknown' }} <br>
                    <i class="fas fa-tag mr-1"></i> {{ $course->kategori->nama ?? '-' }}
                </p>

                <p class="text-sm text-gray-600">
                    {{ Str::limit($course->deskripsi, 90) }}
                </p>

                <!-- Info -->
                <div class="flex justify-between items-center text-xs">

                    <span class="px-2 py-1 rounded-lg
                        {{ $course->harga > 0
                            ? 'bg-amber-100 text-amber-700'
                            : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $course->harga > 0
                            ? 'Rp ' . number_format($course->harga, 0, ',', '.')
                            : 'Gratis' }}
                    </span>

                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg">
                        {{ $course->lessons->count() }} Lesson
                    </span>
                </div>

                <!-- Button -->
                <a href="{{ route('courses.show', $course) }}"
                   class="block text-center mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700">
                    Lihat Detail
                </a>

            </div>
        </div>

        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Belum ada kursus</p>
        </div>
        @endforelse

    </div>

    <!-- Pagination -->
    <div class="pt-4">
        {{ $courses->withQueryString()->links() }}
    </div>

</div>
@endsection
