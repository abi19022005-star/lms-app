@extends('layouts.app')

@section('title', 'Laporan Kursus')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-chalkboard mr-3 text-blue-600"></i>Laporan Kursus
            </h1>
            <p class="text-sm text-gray-500 mt-1">Statistik dan data kursus</p>
        </div>
        <a href="{{ route('admin.reports.export', ['type' => 'courses']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm rounded-xl hover:bg-emerald-700">
            <i class="fas fa-file-excel"></i>
            <span>Export CSV</span>
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Kursus</h2>
        </div>

        @if($courses->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Guru</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Harga</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Lesson</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Enrollments</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Avg Progress</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($courses as $index => $course)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $courses->firstItem() + $index }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ Str::limit($course->judul, 40) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $course->guru->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $course->kategori->nama }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($course->harga > 0)
                                        Rp {{ number_format($course->harga, 0, ',', '.') }}
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">Gratis</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">{{ $course->lessons_count ?? 0 }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-full">{{ $course->enrollments_count ?? 0 }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $course->status == 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-gray-700">
                                    @php $avgProgress = $course->enrollments_sum_progress ?? 0; @endphp
                                    @if($course->enrollments_count > 0)
                                        {{ number_format($avgProgress / $course->enrollments_count, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $courses->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                <p class="text-gray-600 font-medium">Belum ada data kursus</p>
            </div>
        @endif
    </div>

</div>
@endsection
