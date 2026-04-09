@extends('layouts.app')

@section('title', $course->judul)

@section('content')
<div class="space-y-6">

    <!-- HEADER -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        @if($course->thumbnail)
            <img src="{{ Storage::url($course->thumbnail) }}"
                 class="w-full max-h-[400px] object-cover">
        @endif

        <div class="p-6">
            <h1 class="text-xl font-bold text-gray-800">{{ $course->judul }}</h1>

            <p class="text-sm text-gray-500 mt-2">
                <i class="fas fa-user mr-1"></i> {{ $course->guru->name }} |
                <i class="fas fa-tag mr-1"></i> {{ $course->kategori->nama }} |
                <i class="fas fa-clock mr-1"></i> {{ $course->updated_at->diffForHumans() }}
            </p>

            <hr class="my-4">

            <h5 class="font-semibold text-gray-700">Deskripsi Kursus</h5>
            <p class="text-sm text-gray-600 mt-1">
                {{ $course->deskripsi }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- MAIN -->
        <div class="lg:col-span-2 space-y-6">

            <!-- PROGRESS -->
            @if(isset($enrollment) && $enrollment)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">

                <h5 class="font-semibold text-gray-700 mb-3">
                    <i class="fas fa-chart-line mr-2"></i> Progres Belajar Anda
                </h5>

                <div class="w-full bg-gray-100 rounded-full h-6 mb-4">
                    <div class="bg-emerald-500 h-6 rounded-full flex items-center justify-center text-white text-xs"
                         style="width: {{ $progress }}%">
                        {{ $progress }}%
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 text-center text-sm">
                    <div class="border rounded-xl p-3">
                        <h3>{{ $completedLessons ?? 0 }}</h3>
                        <p class="text-gray-400 text-xs">Lesson Selesai</p>
                    </div>
                    <div class="border rounded-xl p-3">
                        <h3>{{ $totalLessons ?? $course->lessons->count() }}</h3>
                        <p class="text-gray-400 text-xs">Total Lesson</p>
                    </div>
                    <div class="border rounded-xl p-3">
                        <h3>{{ $progress }}%</h3>
                        <p class="text-gray-400 text-xs">Progres</p>
                    </div>
                </div>

                @if($progress == 100 && isset($quiz))
                <div class="mt-4 text-center">
                    <a href="{{ route('quizzes.attempt', $quiz) }}"
                       class="px-5 py-2 bg-emerald-600 text-white rounded-xl">
                        <i class="fas fa-check-circle mr-2"></i> Ikuti Kuis Akhir
                    </a>
                </div>
                @endif
            </div>

            @elseif(auth()->check() && auth()->user()->isSiswa())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
                <h5 class="font-semibold">Belum Terdaftar</h5>
                <p class="text-gray-500">Ambil kursus ini untuk mulai belajar!</p>

                <form action="{{ route('courses.enroll', $course) }}" method="POST">
                    @csrf
                    <button class="mt-3 px-5 py-2 bg-blue-600 text-white rounded-xl">
                        <i class="fas fa-shopping-cart mr-2"></i> Ambil Kursus Ini
                    </button>
                </form>
            </div>
            @endif

            <!-- LESSON -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">

                <div class="px-5 py-4 border-b">
                    <h5 class="font-semibold text-gray-700">
                        <i class="fas fa-list mr-2"></i> Daftar Lesson
                    </h5>
                </div>

                <div class="divide-y">
                    @foreach($course->lessons as $index => $lesson)
                    <div class="p-4">

                        <div class="flex justify-between items-center">
                            <div>
                                <strong>{{ $index + 1 }}. {{ $lesson->judul }}</strong>
                                <p class="text-xs text-gray-400 mt-1">
                                    @if($lesson->tipe == 'video')
                                        🎥 Video
                                    @elseif($lesson->tipe == 'teks')
                                        📄 Teks
                                    @else
                                        📕 PDF
                                    @endif
                                </p>
                            </div>

                            @if(isset($enrollment) && $enrollment)
                                @if($lesson->isCompletedByUser(auth()->id()))
                                    <span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-700 rounded-lg">
                                        ✔ Selesai
                                    </span>
                                @else
                                    <button class="complete-lesson px-3 py-1 text-xs bg-emerald-500 text-white rounded-lg"
                                            data-lesson-id="{{ $lesson->id }}">
                                        Tandai
                                    </button>
                                @endif
                            @endif
                        </div>

                        <!-- CONTENT -->
                        @if($lesson->tipe == 'teks' && $lesson->konten_teks)
                        <div class="mt-2 p-3 bg-gray-50 rounded">
                            {!! Str::limit($lesson->konten_teks, 200) !!}
                        </div>
                        @elseif($lesson->tipe == 'video')
                        <a href="{{ $lesson->url_video }}" target="_blank"
                           class="text-blue-600 text-sm mt-2 inline-block">
                            ▶ Tonton Video
                        </a>
                        @elseif($lesson->tipe == 'pdf')
                        <a href="{{ Storage::url($lesson->file_pdf) }}" target="_blank"
                           class="text-red-600 text-sm mt-2 inline-block">
                            📄 Baca PDF
                        </a>
                        @endif

                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="space-y-6">

            <!-- INFO -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h5 class="font-semibold mb-3">Informasi Kursus</h5>

                <div class="space-y-2 text-sm">
                    <p>Lesson: {{ $course->lessons->count() }}</p>
                    <p>Status: {{ ucfirst($course->status) }}</p>
                    <p>
                        Harga:
                        {{ $course->harga > 0 ? 'Rp ' . number_format($course->harga,0,',','.') : 'Gratis' }}
                    </p>
                    <p>Siswa: {{ $course->enrollments->count() }}</p>
                </div>
            </div>

            <!-- QUIZ -->
            @if(isset($quiz))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h5 class="font-semibold mb-3">Kuis Akhir</h5>

                <p><strong>{{ $quiz->judul }}</strong></p>
                <p class="text-sm text-gray-500">Passing: {{ $quiz->passing_score }}</p>
                <p class="text-sm text-gray-500">Soal: {{ $quiz->questions->count() }}</p>

                @if(isset($enrollment) && $enrollment->status == 'completed')
                    @php
                        $attempt = $quiz->getAttemptByUser(auth()->id());
                    @endphp

                    @if($attempt && $attempt->submitted_at)
                        <div class="mt-3 p-3 bg-blue-50 rounded">
                            Sudah dikerjakan
                        </div>
                    @elseif(!$attempt)
                        <a href="{{ route('quizzes.attempt', $quiz) }}"
                           class="block mt-3 text-center px-4 py-2 bg-amber-500 text-white rounded-xl">
                            Mulai Kuis
                        </a>
                    @endif
                @endif
            </div>
            @endif

            <!-- GURU MENU -->
            @if(auth()->check() && (auth()->user()->isGuru() || auth()->user()->isAdmin()) && auth()->id() == $course->guru_id)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h5 class="font-semibold mb-3 text-red-600">Menu Guru</h5>

                <a href="{{ route('courses.edit', $course) }}"
                   class="block mb-2 px-4 py-2 bg-amber-500 text-white rounded-xl text-center">
                    Edit
                </a>

                <a href="{{ route('lessons.create', $course) }}"
                   class="block mb-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-center">
                    Tambah Lesson
                </a>

                <form action="{{ route('courses.destroy', $course) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="w-full px-4 py-2 bg-red-600 text-white rounded-xl"
                            onclick="return confirm('Yakin?')">
                        Hapus
                    </button>
                </form>
            </div>
            @endif

        </div>

    </div>

</div>
@endsection
