@extends('layouts.app')

@section('title', $course->judul . ' - Daftar Siswa')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">{{ $course->judul }}</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar siswa yang terdaftar di kursus ini</p>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Siswa</h2>
            <a href="{{ route('courses.students.export', $course) }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                <i class="fas fa-download"></i>
                <span>Export CSV</span>
            </a>
        </div>

        <!-- Table -->
        @if($students->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $enrollment)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $enrollment->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $enrollment->user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $enrollment->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-32">
                                        <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ $enrollment->progress }}%</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $enrollment->enrolled_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $students->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                <p class="text-gray-600 font-medium">Belum ada siswa yang terdaftar</p>
            </div>
        @endif
    </div>

</div>
@endsection
