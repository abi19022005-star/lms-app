@extends('layouts.app')

@section('title', 'Manajemen Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-users mr-3 text-blue-600"></i>Manajemen Siswa
            </h1>
            <p class="text-sm text-gray-500 mt-1">Lihat semua siswa yang terdaftar dan kursus yang mereka ambil</p>
        </div>
        <a href="{{ route('admin.students.export', request()->query()) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export CSV
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-blue-100">Total Siswa</p>
                    <p class="text-3xl font-bold mt-1">{{ $students->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-graduate text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-emerald-100">Siswa Aktif</p>
                    <p class="text-3xl font-bold mt-1">
                        {{ $students->filter(function($student) {
                            return $student->enrollments->where('status', 'active')->count() > 0;
                        })->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-600 to-purple-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-purple-100">Total Enrollment</p>
                    <p class="text-3xl font-bold mt-1">
                        {{ $students->sum(function($student) { return $student->enrollments->count(); }) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book-open text-xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-600 to-amber-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-amber-100">Sertifikat Diterbitkan</p>
                    <p class="text-3xl font-bold mt-1">
                        {{ $students->sum(function($student) { return $student->certificates->count(); }) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-certificate text-xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari siswa</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Nama atau email...">
            </div>
            <div class="min-w-[180px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Filter Kursus</label>
                <select name="course_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kursus</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->judul }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
            <div>
                <a href="{{ route('admin.students.index') }}" class="inline-flex px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-all">
                    <i class="fas fa-sync mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h6 class="text-sm font-semibold text-gray-700">
                    <i class="fas fa-list mr-2"></i> Daftar Siswa
                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $students->total() }} Total</span>
                </h6>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Kursus Diambil</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Kursus Selesai</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rata-rata Progres</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Sertifikat</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Bergabung</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $index => $student)
                        @php
                            $totalEnrollments = $student->enrollments->count();
                            $completedEnrollments = $student->enrollments->where('status', 'completed')->count();
                            $avgProgress = $student->enrollments->avg('progress') ?? 0;
                            $certificateCount = $student->certificates->count();
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-5 py-3 text-sm text-gray-500">
                                {{ $students->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @if($student->foto)
                                        <img src="{{ Storage::url($student->foto) }}"
                                             class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium shadow-sm">
                                            {{ strtoupper(substr($student->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-400">ID: #{{ $student->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">
                                {{ $student->email }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700">
                                    {{ $totalEnrollments }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($completedEnrollments > 0)
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-emerald-100 text-emerald-700">
                                        {{ $completedEnrollments }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if($totalEnrollments > 0)
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[100px]">
                                            <div class="rounded-full h-2 transition-all duration-500
                                                {{ $avgProgress >= 70 ? 'bg-emerald-500' : ($avgProgress >= 40 ? 'bg-amber-500' : 'bg-red-500') }}"
                                                style="width: {{ $avgProgress }}%">
                                            </div>
                                        </div>
                                        <span class="text-xs font-medium {{ $avgProgress >= 70 ? 'text-emerald-600' : ($avgProgress >= 40 ? 'text-amber-600' : 'text-red-600') }}">
                                            {{ number_format($avgProgress, 0) }}%
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Belum ambil kursus</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($certificateCount > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-lg bg-purple-100 text-purple-700">
                                        <i class="fas fa-certificate text-xs"></i>
                                        {{ $certificateCount }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="text-sm text-gray-600">{{ $student->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $student->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('admin.students.show', $student->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                                   title="Detail Siswa">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center">
                                <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <h5 class="mt-4 text-lg font-medium text-gray-700">Tidak ada data siswa</h5>
                                <p class="text-gray-400 mt-1">Belum ada siswa yang terdaftar</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-3">
            <div class="text-xs text-gray-500">
                Menampilkan {{ $students->firstItem() }} sampai {{ $students->lastItem() }} dari {{ $students->total() }} data
            </div>
            <div>
                {{ $students->withQueryString()->links() }}
            </div>
        </div>
    </div>
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
