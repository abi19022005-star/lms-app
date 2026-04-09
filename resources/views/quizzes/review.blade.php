@extends('layouts.app')

@section('title', $quiz->judul . ' - Review')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <a href="{{ route('courses.show', $quiz->course) }}"
       class="inline-flex items-center px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Kursus
    </a>

    <!-- Quiz Status Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->judul }}<span class="text-base font-normal text-gray-500 ml-3">Review</span></h1>
                <p class="text-sm text-gray-500 mt-1">Attempt ID: {{ $attempt->id }}</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-4 py-2 text-sm font-semibold rounded-full {{ $attempt->submitted_at ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $attempt->submitted_at ? '✓ Submitted' : '⟳ Pending' }}
                </span>
                <p class="text-xs text-gray-500 mt-2">{{ $attempt->submitted_at?->format('d M Y H:i') ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Questions Review -->
    <div class="space-y-4">
        @forelse($questions as $index => $question)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <!-- Question Header -->
                <div class="flex items-start justify-between mb-4 pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Soal {{ $index + 1 }}</h3>
                        <p class="text-sm text-gray-600 mt-2">{{ $question->pertanyaan }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $question->tipe == 'multiple_choice' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                        {{ $question->tipe == 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                    </span>
                </div>

                <!-- Answer Review -->
                <div class="space-y-3">
                    @if($question->tipe === 'multiple_choice')
                        @php
                            $answer = $question->answers->first();
                            $isCorrect = $answer && $answer->is_correct;
                        @endphp
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Jawaban Anda</p>
                            <div class="inline-block px-3 py-2 rounded-lg {{ $isCorrect ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                <span class="text-sm font-semibold">
                                    @if($isCorrect)
                                        <i class="fas fa-check mr-1"></i>
                                    @else
                                        <i class="fas fa-times mr-1"></i>
                                    @endif
                                    {{ $answer->jawaban_text ?? 'Tidak dijawab' }}
                                </span>
                            </div>
                        </div>
                        @if(!$isCorrect)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Jawaban Benar</p>
                                <div class="inline-block px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700">
                                    <span class="text-sm font-semibold">{{ $question->jawaban_benar }}</span>
                                </div>
                            </div>
                        @endif
                    @else
                        @php
                            $answer = $question->answers->first();
                        @endphp
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Jawaban Anda</p>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-sm text-gray-700">
                                {{ $answer->jawaban_text ?? 'Tidak dijawab' }}
                            </div>
                        </div>
                        @if($answer && $answer->score)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Skor</p>
                                <div class="inline-block px-4 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold">
                                    {{ $answer->score }}/100
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-blue-50 text-blue-700 text-sm p-6 rounded-xl border border-blue-200 text-center">
                <i class="fas fa-inbox text-3xl mb-3"></i>
                <p class="font-semibold">Tidak ada soal ditemukan</p>
            </div>
        @endforelse
    </div>

    <!-- Back Button -->
    <div>
        <a href="{{ route('courses.show', $quiz->course) }}"
           class="inline-flex items-center px-6 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Kursus
        </a>
    </div>

</div>
@endsection
