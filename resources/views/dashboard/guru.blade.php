@extends('layouts.app')

@section('title', 'Guru Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-chalkboard-teacher mr-3 text-amber-600"></i>Guru Dashboard
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Selamat datang, {{ auth()->user()->name }} 👋
        </p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-blue-600 text-white rounded-2xl p-5 shadow-sm">
            <p class="text-sm opacity-80">Total Courses</p>
            <h2 class="text-2xl font-bold">{{ $courses->count() }}</h2>
        </div>

        <div class="bg-emerald-600 text-white rounded-2xl p-5 shadow-sm">
            <p class="text-sm opacity-80">Total Students</p>
            <h2 class="text-2xl font-bold">{{ $totalStudents ?? 0 }}</h2>
        </div>

        <div class="bg-cyan-600 text-white rounded-2xl p-5 shadow-sm">
            <p class="text-sm opacity-80">Pending Grading</p>
            <h2 class="text-2xl font-bold">{{ $pendingGrading ?? 0 }}</h2>
        </div>

        <div class="bg-amber-500 text-white rounded-2xl p-5 shadow-sm">
            <p class="text-sm opacity-80">Revenue</p>
            <h2 class="text-xl font-bold">
                Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
            </h2>
        </div>

    </div>

    <!-- Course Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b flex justify-between items-center">
            <h6 class="text-sm font-semibold text-gray-700">
                <i class="fas fa-book-open mr-2"></i>My Courses
            </h6>

            <a href="{{ route('courses.create') }}"
               class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                <i class="fas fa-plus mr-1"></i> New Course
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left">Course</th>
                        <th class="px-5 py-3 text-left">Students</th>
                        <th class="px-5 py-3 text-left">Progress</th>
                        <th class="px-5 py-3 text-left">Revenue</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($courseStats ?? [] as $stat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">
                                {{ $stat['course']->judul }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $stat['course']->kategori->nama }}
                            </p>
                        </td>

                        <td class="px-5 py-3">
                            {{ $stat['total_enrolled'] }}
                        </td>

                        <td class="px-5 py-3">
                            <div class="w-full bg-gray-100 rounded-full h-2 mb-1">
                                <div class="bg-emerald-500 h-2 rounded-full"
                                     style="width: {{ $stat['completion_rate'] }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500">
                                {{ number_format($stat['avg_progress'], 0) }}%
                            </p>
                        </td>

                        <td class="px-5 py-3">
                            Rp {{ number_format($stat['course']->harga, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-3">
                            <span class="px-2 py-1 text-xs rounded-lg
                                {{ $stat['course']->status == 'published'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($stat['course']->status) }}
                            </span>
                        </td>

                        <td class="px-5 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('courses.show', $stat['course']) }}"
                                   class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('courses.edit', $stat['course']) }}"
                                   class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <a href="{{ route('lessons.create', $stat['course']) }}"
                                   class="w-8 h-8 flex items-center justify-center bg-cyan-50 text-cyan-600 rounded-lg hover:bg-cyan-100">
                                    <i class="fas fa-plus text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10">
                            <i class="fas fa-folder-open text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Belum ada kursus</p>
                            <a href="{{ route('courses.create') }}"
                               class="mt-3 inline-flex px-4 py-2 bg-blue-600 text-white rounded-xl text-sm">
                                Buat Kursus
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Students -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h6 class="text-sm font-semibold text-gray-700 mb-4">
            <i class="fas fa-trophy mr-2"></i>Top Students
        </h6>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($topStudents ?? [] as $index => $student)

            <div class="text-center">
                <div class="relative inline-block">
                    @if($index == 0)
                        <i class="fas fa-crown text-yellow-500 absolute -top-3 left-1/2 -translate-x-1/2"></i>
                    @endif

                    @if($student->user->foto)
                        <img src="{{ Storage::url($student->user->foto) }}"
                             class="w-16 h-16 rounded-full object-cover mx-auto">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center mx-auto">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                    @endif
                </div>

                <p class="text-sm font-medium mt-2">
                    {{ $student->user->name }}
                </p>
                <p class="text-xs text-gray-400">
                    {{ number_format($student->avg_progress, 0) }}%
                </p>
            </div>

            @empty
            <div class="col-span-full text-center py-6 text-gray-400">
                Belum ada siswa
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
