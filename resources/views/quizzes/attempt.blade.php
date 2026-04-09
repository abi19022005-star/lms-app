@extends('layouts.app')

@section('title', $quiz->judul)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold font-poppins">{{ $quiz->judul }}</h1>
                <p class="text-blue-100 mt-1">Passing Score: {{ $quiz->passing_score }}%</p>
            </div>
            <div class="hidden md:block">
                <div class="text-4xl">📝</div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 text-blue-700 text-sm p-4 rounded-xl border border-blue-200">
        <p class="flex items-center gap-2 font-semibold mb-2">
            <i class="fas fa-info-circle"></i>
            <span>Petunjuk Penting</span>
        </p>
        <ul class="list-disc list-inside space-y-1 text-xs">
            <li>Jawab semua soal dengan teliti dan jujur</li>
            <li>Untuk soal essay, tulis jawaban dengan jelas dan terstruktur</li>
            <li>Setelah Anda submit, jawaban tidak dapat diubah lagi</li>
        </ul>
    </div>

    <!-- Quiz Form -->
    <form action="{{ route('quizzes.submit', $quiz) }}" method="POST" id="quizForm" class="space-y-6">
        @csrf

        @foreach($questions as $index => $question)
            <div class="bg-white rounded-2xl shadow-sm border {{ $errors->has('answers.' . $question->id) ? 'border-red-500' : 'border-gray-100' }} p-6">
                <!-- Question Header -->
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Soal {{ $index + 1 }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $question->pertanyaan }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $question->tipe == 'multiple_choice' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $question->tipe == 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                    </span>
                </div>

                <!-- Question Body -->
                @if($question->tipe == 'multiple_choice')
                    @php
                        $options = is_array($question->opsi) ? $question->opsi : json_decode($question->opsi, true);
                    @endphp
                    <div class="space-y-2">
                        @foreach($options as $key => $option)
                            <label class="flex items-center p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio"
                                       name="answers[{{ $question->id }}]"
                                       id="q{{ $question->id }}_{{ $loop->index }}"
                                       value="{{ $option }}"
                                       {{ old('answers.' . $question->id) == $option ? 'checked' : '' }}
                                       required
                                       class="w-4 h-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <textarea name="answers[{{ $question->id }}]"
                              rows="5"
                              placeholder="Tulis jawaban Anda di sini dengan jelas dan terstruktur..."
                              class="w-full px-3 py-2 text-sm border {{ $errors->has('answers.' . $question->id) ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:ring-2 focus:ring-blue-500"
                              required>{{ old('answers.' . $question->id) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Minimal 10 karakter</p>
                @endif

                <!-- Error Message -->
                @error('answers.' . $question->id)
                    <div class="mt-3 p-3 bg-red-50 text-red-700 text-xs rounded-xl border border-red-200">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </div>
                @enderror
            </div>
        @endforeach

        <!-- Warning Alert -->
        <div class="bg-amber-50 text-amber-700 text-sm p-4 rounded-xl border border-amber-200">
            <p class="flex items-center gap-2 font-semibold">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Perhatian!</span>
            </p>
            <p class="text-xs mt-1">Pastikan semua pertanyaan telah dijawab sebelum Anda mengirimkan kuis. Anda tidak dapat mengubah jawaban setelah submit.</p>
        </div>

        <!-- Actions -->
        <div class="flex justify-between gap-4">
            <a href="{{ route('courses.show', $quiz->course) }}"
               class="px-6 py-2 text-sm bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Batal
            </a>
            <button type="submit" onclick="return confirmSubmit()" class="px-6 py-2 text-sm bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-medium">
                <i class="fas fa-paper-plane mr-1"></i> Submit Kuis
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function confirmSubmit() {
        return confirm('Apakah Anda yakin ingin mengirim jawaban? Anda tidak dapat mengubah jawaban setelah submit.');
    }

    // Auto-save untuk essay (optional)
    let autoSaveTimer;
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                // Auto save to localStorage
                const name = this.name;
                const value = this.value;
                localStorage.setItem(name, value);
            }, 1000);
        });

        // Load from localStorage
        const savedValue = localStorage.getItem(this.name);
        if (savedValue) {
            this.value = savedValue;
        }
    });
</script>
@endpush
@endsection
