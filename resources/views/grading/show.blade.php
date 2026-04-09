@extends('layouts.app')

@section('title', 'Penilaian: ' . $attempt->user->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-edit mr-3 text-amber-600"></i>Penilaian Kuis
            </h1>
            <p class="text-sm text-gray-500 mt-1">Berikan nilai untuk jawaban essay siswa</p>
        </div>
        <a href="{{ route('grading.index') }}" class="inline-flex items-center px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Student Info Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Siswa</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-center gap-4">
                @if($attempt->user->foto)
                    <img src="{{ Storage::url($attempt->user->foto) }}" class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-gray-600"></i>
                    </div>
                @endif
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $attempt->user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $attempt->user->email }}</p>
                </div>
            </div>
            <div class="space-y-2">
                <p class="text-xs font-semibold text-gray-500 uppercase">Kursus</p>
                <p class="text-sm font-medium text-gray-800">{{ $attempt->quiz->course->judul }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-xs font-semibold text-gray-500 uppercase">Kuis</p>
                <p class="text-sm font-medium text-gray-800">{{ $attempt->quiz->judul }}</p>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-gray-500">Passing Score</p>
                <p class="text-lg font-bold text-gray-800">{{ $attempt->quiz->passing_score }}%</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Submitted</p>
                <p class="text-sm text-gray-800">{{ $attempt->submitted_at->format('d M Y H:i') }}</p>
            </div>
            <div>
               <p class="text-xs text-gray-500">Time Ago</p>
                <p class="text-sm text-gray-800">{{ $attempt->submitted_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('grading.grade', $attempt) }}" method="POST" class="space-y-6">
        @csrf

        <!-- Multiple Choice Answers (Read Only) -->
        @if($mcqAnswers->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-check-circle mr-2 text-emerald-600"></i>Pilihan Ganda (Otomatis Dinilai)
                </h2>

                <div class="bg-emerald-50 text-emerald-700 text-sm p-4 rounded-xl border border-emerald-200 mb-4">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>Skor Pilihan Ganda: {{ $mcqScore }}/{{ $maxMcqScore }}</strong> ({{ number_format(($mcqScore / max($maxMcqScore, 1)) * 100, 0) }}%)
                </div>

                <div class="space-y-4">
                    @foreach($mcqAnswers as $index => $answer)
                        <div class="p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 mb-2">Soal {{ $index + 1 }}</h4>
                                    <p class="text-sm text-gray-700 mb-3">{{ $answer->question->pertanyaan }}</p>
                                    <div class="space-y-1">
                                        <p class="text-xs font-semibold text-gray-500">Jawaban Siswa:</p>
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $answer->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $answer->jawaban_text }}
                                        </span>
                                    </div>
                                    @if(!$answer->is_correct)
                                        <p class="text-xs text-red-600 mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Jawaban benar: <strong>{{ $answer->question->jawaban_benar }}</strong>
                                        </p>
                                    @else
                                        <p class="text-xs text-emerald-600 mt-2">✓ Jawaban benar</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-4 py-2 text-sm font-bold rounded-xl {{ $answer->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $answer->score ?? 0 }}/1
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Essay Questions (To Grade) -->
        @if($essayAnswers->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-pen mr-2 text-amber-600"></i>Soal Essay (Perlu Dinilai)
                </h2>

                <div class="space-y-6">
                    @foreach($essayAnswers as $index => $answer)
                        <div class="p-4 border border-gray-200 rounded-xl">
                            <h4 class="font-semibold text-gray-800 mb-2">Soal {{ $index + 1 }}</h4>
                            <p class="text-sm text-gray-700 mb-4">{{ $answer->question->pertanyaan }}</p>

                            <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Jawaban Siswa</p>
                                <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $answer->jawaban_text }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nilai (0-100)</label>
                                    <input type="number"
                                           name="scores[{{ $answer->id }}]"
                                           class="w-full px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('scores.' . $answer->id) border-red-500 @enderror"
                                           value="{{ old('scores.' . $answer->id, $answer->score) }}"
                                           min="0" max="100" step="1" required>
                                    <p class="text-xs text-gray-400 mt-1">Berikan nilai sesuai kualitas jawaban</p>
                                    @error('scores.' . $answer->id)
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Feedback (Opsional)</label>
                                    <textarea name="feedback[{{ $answer->id }}]"
                                              class="w-full px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                                              rows="3"
                                              placeholder="Berikan komentar untuk siswa...">{{ old('feedback.' . $answer->id, $answer->feedback ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Scoring Guide -->
                <div class="bg-blue-50 text-blue-700 text-sm p-4 rounded-xl border border-blue-200 mt-4">
                    <p class="flex items-center gap-2 font-semibold mb-2">
                        <i class="fas fa-lightbulb"></i>
                        <span>Panduan Penilaian Essay</span>
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>90-100: Jawaban sangat baik, lengkap, dan mendalam</li>
                        <li>75-89: Jawaban baik, sebagian besar poin terjawab</li>
                        <li>60-74: Jawaban cukup, poin penting terjawab</li>
                        <li>50-59: Jawaban kurang, banyak poin tidak terjawab</li>
                        <li>&lt;50: Jawaban tidak memenuhi kriteria</li>
                    </ul>
                </div>
            </div>
        @endif

        <!-- Summary & Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Nilai</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-700">Pilihan Ganda</span>
                        <strong class="text-lg text-gray-800">{{ $mcqScore }}/{{ $maxMcqScore }} ({{ number_format(($mcqScore / max($maxMcqScore, 1)) * 100, 0) }}%)</strong>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-700">Essay</span>
                        <span id="essay-summary" class="text-sm text-amber-600 font-semibold">Belum dinilai</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <span class="text-sm font-semibold text-blue-700">Total</span>
                        <strong id="total-summary" class="text-lg text-blue-700">-</strong>
                    </div>
                </div>
                <div class="flex flex-col gap-3 justify-end">
                    <a href="{{ route('grading.index') }}" class="px-6 py-2 text-sm bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 text-center font-medium">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" class="px-6 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-medium">
                        <i class="fas fa-save mr-1"></i> Simpan Nilai
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Live calculation of essay scores
    const essayInputs = document.querySelectorAll('input[name^="scores"]');
    const essaySummary = document.getElementById('essay-summary');
    const totalSummary = document.getElementById('total-summary');
    const mcqScore = {{ $mcqScore }};
    const maxMcqScore = {{ $maxMcqScore }};
    const essayCount = {{ $essayAnswers->count() }};
    const passingScore = {{ $attempt->quiz->passing_score }};

    function updateSummary() {
        let totalEssayScore = 0;
        let gradedCount = 0;

        essayInputs.forEach(input => {
            let value = parseFloat(input.value);
            if (!isNaN(value)) {
                totalEssayScore += value;
                gradedCount++;
            }
        });

        let avgEssayScore = gradedCount > 0 ? totalEssayScore / gradedCount : 0;
        let essayPercentage = avgEssayScore;

        // Calculate total score (MCQ 50% + Essay 50% if both exist)
        let mcqPercentage = (mcqScore / maxMcqScore) * 100;
        let totalPercentage;

        if (essayCount > 0 && maxMcqScore > 0) {
            // Weighted: MCQ 50%, Essay 50%
            totalPercentage = (mcqPercentage * 0.5) + (essayPercentage * 0.5);
        } else if (essayCount > 0) {
            totalPercentage = essayPercentage;
        } else {
            totalPercentage = mcqPercentage;
        }

        essaySummary.innerHTML = gradedCount === essayCount
            ? `<strong>${avgEssayScore.toFixed(1)}</strong>% (${essayPercentage.toFixed(1)}%)`
            : `<span class="text-amber-600">${gradedCount}/${essayCount} essay dinilai</span>`;

        const statusColor = totalPercentage >= passingScore ? 'text-emerald-700' : 'text-red-700';
        totalSummary.innerHTML = `<strong class="${statusColor}">
            ${totalPercentage.toFixed(1)}%
        </strong> (Passing: ${passingScore}%)`;
    }

    essayInputs.forEach(input => {
        input.addEventListener('input', updateSummary);
    });

    updateSummary();
</script>
@endpush
@endsection
