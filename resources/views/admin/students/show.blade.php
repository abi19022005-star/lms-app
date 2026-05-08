@extends('layouts.app')

@section('title', 'Detail Siswa: ' . $student->name)

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.students.index') }}"
                   class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                        <i class="fas fa-user-graduate mr-3 text-blue-600"></i>Detail Siswa
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Informasi lengkap tentang {{ $student->name }}</p>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.students.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left Column - Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-8 text-center">
                    <div class="relative inline-block">
                        @if($student->foto)
                            <img src="{{ Storage::url($student->foto) }}" alt="{{ $student->name }}"
                                 class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-28 h-28 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white shadow-lg">
                                <i class="fas fa-user-graduate text-5xl text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h2 class="text-white text-xl font-bold mt-4">{{ $student->name }}</h2>
                    <p class="text-blue-100 text-sm">{{ $student->email }}</p>
                    <div class="mt-3">
                        <span class="inline-flex px-3 py-1 bg-white/20 rounded-full text-xs text-white">
                            <i class="fas fa-graduation-cap mr-1"></i> Siswa
                        </span>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                        <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Bergabung Sejak</p>
                            <p class="text-sm text-gray-700">{{ $student->created_at->translatedFormat('d F Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $student->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                        <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-id-card text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">ID Siswa</p>
                            <p class="text-sm text-gray-700 font-mono">#{{ $student->id }}</p>
                        </div>
                    </div>
                    @if($student->bio)
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-align-left text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Bio</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $student->bio }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid gap-4 grid-cols-3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-book-open text-blue-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalEnrollments }}</p>
                    <p class="text-xs text-gray-500">Kursus Diambil</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $completedCourses }}</p>
                    <p class="text-xs text-gray-500">Kursus Selesai</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-certificate text-purple-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalCertificates }}</p>
                    <p class="text-xs text-gray-500">Sertifikat</p>
                </div>
            </div>
        </div>

        <!-- Right Column - Courses -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Active Courses -->
            @if($activeCourses->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-white">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-spinner text-amber-600"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800">Kursus Dalam Progres</h3>
                        <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs rounded-full">{{ $activeCourses->count() }} Kursus</span>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($activeCourses as $enrollment)
                    <div class="p-5 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $enrollment->course->judul }}</h4>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-chalkboard-teacher mr-1"></i> {{ $enrollment->course->guru->name }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        <i class="fas fa-calendar-alt mr-1"></i> {{ $enrollment->enrolled_at->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('courses.show', $enrollment->course) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fas fa-eye"></i> Lihat Kursus
                            </a>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-3">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Progress Belajar</span>
                                <span class="font-medium {{ $enrollment->progress >= 70 ? 'text-emerald-600' : ($enrollment->progress >= 40 ? 'text-amber-600' : 'text-red-600') }}">
                                    {{ $enrollment->progress }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="rounded-full h-2.5 transition-all duration-500
                                    {{ $enrollment->progress >= 70 ? 'bg-emerald-500' : ($enrollment->progress >= 40 ? 'bg-amber-500' : 'bg-red-500') }}"
                                    style="width: {{ $enrollment->progress }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Completed Courses -->
            @if($completedCoursesList->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-trophy text-emerald-600"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800">Kursus Selesai</h3>
                        <span class="ml-2 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded-full">{{ $completedCoursesList->count() }} Kursus</span>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($completedCoursesList as $enrollment)
                    <div class="p-5 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-semibold text-gray-800">{{ $enrollment->course->judul }}</h4>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Selesai
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-chalkboard-teacher mr-1"></i> {{ $enrollment->course->guru->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-calendar-check mr-1"></i> Selesai: {{ $enrollment->completed_at ? $enrollment->completed_at->translatedFormat('d M Y') : '-' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @php
                                    $certificate = $student->certificates->where('course_id', $enrollment->course_id)->first();
                                @endphp
                                @if($certificate)
                                <a href="{{ route('certificates.download', $certificate) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                                    <i class="fas fa-download"></i> Sertifikat
                                </a>
                                @endif
                                <a href="{{ route('courses.show', $enrollment->course) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                        </div>

                        <!-- Completion Badge -->
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-emerald-500 rounded-full h-2" style="width: 100%"></div>
                                </div>
                                <span class="text-xs font-medium text-emerald-600">100%</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Empty State -->
            @if($activeCourses->count() == 0 && $completedCoursesList->count() == 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-700">Belum Ada Kursus</h3>
                <p class="text-gray-400 mt-1">Siswa belum mengambil kursus apapun</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom styles if needed */
</style>
@endpush
