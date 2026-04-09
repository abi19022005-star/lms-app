@extends('layouts.app')

@section('title', 'Penilaian Kuis')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-clipboard-list mr-3 text-amber-600"></i>Penilaian Kuis
        </h1>
        <p class="text-sm text-gray-500 mt-1">Nilai jawaban essay dari siswa</p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="bg-amber-500 text-white rounded-2xl p-5 shadow-sm">
            <p class="text-sm opacity-80">Pending Grading</p>
            <h2 class="text-2xl font-bold">{{ $stats['total_pending'] ?? 0 }}</h2>
        </div>

        <div class="bg-cyan-600 text-white rounded-2xl p-5 shadow-sm">
            <p class="text-sm opacity-80">Total Submissions</p>
            <h2 class="text-2xl font-bold">{{ $stats['total_attempts'] ?? 0 }}</h2>
        </div>

        <div class="bg-emerald-600 text-white rounded-2xl p-5 shadow-sm">
            <p class="text-sm opacity-80">Completed</p>
            <h2 class="text-2xl font-bold">
                {{ ($stats['total_attempts'] ?? 0) - ($stats['total_pending'] ?? 0) }}
            </h2>
        </div>

    </div>

    <!-- Pending -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-5 py-4 border-b">
            <h6 class="text-sm font-semibold text-gray-700">
                <i class="fas fa-clock mr-2"></i> Submissions Need Grading
            </h6>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left">Student</th>
                        <th class="px-5 py-3 text-left">Course</th>
                        <th class="px-5 py-3 text-left">Quiz</th>
                        <th class="px-5 py-3 text-left">Submitted</th>
                        <th class="px-5 py-3 text-left">MCQ</th>
                        <th class="px-5 py-3 text-left">Essay</th>
                        <th class="px-5 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($pendingAttempts ?? [] as $attempt)
                    @php
                        $mcqAnswers = $attempt->answers()->whereHas('question', fn($q) => $q->where('tipe', 'multiple_choice'))->get();
                        $essayAnswers = $attempt->answers()->whereHas('question', fn($q) => $q->where('tipe', 'essay'))->get();
                        $mcqScore = $mcqAnswers->sum('score');
                        $maxMcqScore = $mcqAnswers->count();
                        $gradedEssay = $essayAnswers->whereNotNull('score')->count();
                        $totalEssay = $essayAnswers->count();
                    @endphp

                    <tr class="hover:bg-gray-50">

                        <!-- Student -->
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if($attempt->user->foto)
                                    <img src="{{ Storage::url($attempt->user->foto) }}"
                                         class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500">
                                        <i class="fas fa-user text-xs"></i>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-sm font-medium">{{ $attempt->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $attempt->user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Course -->
                        <td class="px-5 py-3">{{ $attempt->quiz->course->judul }}</td>

                        <!-- Quiz -->
                        <td class="px-5 py-3">{{ $attempt->quiz->judul }}</td>

                        <!-- Submitted -->
                        <td class="px-5 py-3">
                            <p>{{ $attempt->submitted_at->format('d M Y H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $attempt->submitted_at->diffForHumans() }}</p>
                        </td>

                        <!-- MCQ -->
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 text-xs rounded-lg
                                {{ $mcqScore == $maxMcqScore ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $mcqScore }}/{{ $maxMcqScore }}
                            </span>
                        </td>

                        <!-- Essay -->
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 text-xs rounded-lg
                                {{ $gradedEssay == $totalEssay ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $gradedEssay }}/{{ $totalEssay }}
                            </span>
                        </td>

                        <!-- Action -->
                        <td class="px-5 py-3 text-center">
                            <a href="{{ route('grading.show', $attempt) }}"
                               class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Grade
                            </a>
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <i class="fas fa-check-circle text-4xl text-emerald-500 mb-3"></i>
                            <p class="text-gray-600 font-medium">Semua submission sudah dinilai</p>
                            <p class="text-gray-400 text-sm">Tidak ada yang perlu dinilai</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- GRADED -->
    @if(isset($gradedAttempts) && $gradedAttempts->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-5 py-4 border-b">
            <h6 class="text-sm font-semibold text-gray-700">
                <i class="fas fa-history mr-2"></i> Already Graded
            </h6>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left">Student</th>
                        <th class="px-5 py-3 text-left">Course</th>
                        <th class="px-5 py-3 text-left">Score</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Graded</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($gradedAttempts as $attempt)
                    <tr class="hover:bg-gray-50">

                        <td class="px-5 py-3">{{ $attempt->user->name }}</td>
                        <td class="px-5 py-3">{{ $attempt->quiz->course->judul }}</td>

                        <td class="px-5 py-3">
                            <span class="px-2 py-1 text-xs rounded-lg
                                {{ $attempt->total_score >= $attempt->quiz->passing_score
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-red-100 text-red-600' }}">
                                {{ round($attempt->total_score, 2) }}%
                            </span>
                        </td>

                        <td class="px-5 py-3">
                            @if($attempt->total_score >= $attempt->quiz->passing_score)
                                <span class="text-emerald-600 text-sm">Passed</span>
                            @else
                                <span class="text-red-600 text-sm">Failed</span>
                            @endif
                        </td>

                        <td class="px-5 py-3">
                            {{ $attempt->updated_at->format('d M Y H:i') }}
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    @endif

</div>
@endsection
