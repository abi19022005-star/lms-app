@extends('layouts.app')

@section('title', 'Edit Kuis: ' . $quiz->judul)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-edit mr-3 text-amber-600"></i>Edit Kuis: {{ $quiz->judul }}
        </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Quiz Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Kuis</h2>
                <form action="{{ route('quizzes.update', $quiz) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Judul -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Judul Kuis</label>
                        <input type="text"
                               id="judul"
                               name="judul"
                               value="{{ $quiz->judul }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <!-- Passing Score -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Passing Score (%)</label>
                        <input type="number"
                               id="passing_score"
                               name="passing_score"
                               value="{{ $quiz->passing_score }}"
                               min="0"
                               max="100"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                        <i class="fas fa-save mr-1"></i> Update Kuis
                    </button>
                </form>
            </div>

            <!-- Add Question Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Tambah Soal Baru</h2>
                <form action="{{ route('questions.store', $quiz) }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Tipe Soal -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tipe Soal</label>
                        <select id="tipe"
                                name="tipe"
                                onchange="toggleQuestionType()"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="multiple_choice">Pilihan Ganda</option>
                            <option value="essay">Essay</option>
                        </select>
                    </div>

                    <!-- Pertanyaan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Pertanyaan</label>
                        <textarea id="pertanyaan"
                                  name="pertanyaan"
                                  rows="3"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                                  required></textarea>
                    </div>

                    <!-- Multiple Choice Options -->
                    <div id="mcq-options" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-2">Opsi Jawaban</label>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-700 text-xs font-semibold rounded">A</span>
                                    <input type="text" name="opsi[A]" placeholder="Opsi A" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-700 text-xs font-semibold rounded">B</span>
                                    <input type="text" name="opsi[B]" placeholder="Opsi B" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-700 text-xs font-semibold rounded">C</span>
                                    <input type="text" name="opsi[C]" placeholder="Opsi C" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-700 text-xs font-semibold rounded">D</span>
                                    <input type="text" name="opsi[D]" placeholder="Opsi D" class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jawaban Benar</label>
                            <select id="jawaban_benar"
                                    name="jawaban_benar"
                                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                                    required>
                                <option value="">Pilih Jawaban Benar</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="px-4 py-2 text-sm bg-emerald-600 text-white rounded-xl hover:bg-emerald-700">
                        <i class="fas fa-plus mr-1"></i> Tambah Soal
                    </button>
                </form>
            </div>
        </div>

        <!-- Questions List Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Daftar Soal ({{ $quiz->questions->count() }})</h2>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @forelse($quiz->questions as $index => $question)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-700">Soal {{ $index + 1 }}</p>
                                    <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full {{ $question->tipe == 'multiple_choice' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $question->tipe == 'multiple_choice' ? 'PG' : 'Essay' }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $question->pertanyaan }}</p>
                                </div>
                                <form action="{{ route('questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')" class="flex-shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center">
                            <i class="fas fa-inbox text-2xl text-gray-300 mb-2"></i>
                            <p class="text-xs text-gray-500">Belum ada soal</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleQuestionType() {
        const tipe = document.getElementById('tipe').value;
        const mcqOptions = document.getElementById('mcq-options');

        if (tipe === 'multiple_choice') {
            mcqOptions.style.display = 'block';
        } else {
            mcqOptions.style.display = 'none';
        }
    }

    toggleQuestionType();
</script>
@endpush
@endsection
