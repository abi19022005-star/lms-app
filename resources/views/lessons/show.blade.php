{{-- resources/views/lessons/show.blade.php --}}
@extends('layouts.app')

@section('title', $lesson->judul . ' - ' . $course->judul)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="flex flex-col lg:flex-row">
        <!-- Sidebar -->
        <aside class="lg:w-80 bg-white border-r border-gray-200 shadow-sm lg:min-h-screen">
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-500">
                <div class="flex items-center gap-3">
                    <a href="{{ route('courses.show', $course) }}" class="text-white/80 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h3 class="text-white font-semibold truncate">{{ $course->judul }}</h3>
                        <p class="text-blue-100 text-xs mt-0.5">Progress: {{ $courseProgress }}%</p>
                    </div>
                </div>
                <div class="mt-3 bg-white/20 rounded-full h-2">
                    <div class="bg-white rounded-full h-2" style="width: {{ $courseProgress }}%"></div>
                </div>
                <p class="text-blue-100 text-xs mt-2">{{ $completedLessons }} dari {{ $totalLessons }} lesson selesai</p>
            </div>

            <div class="divide-y divide-gray-100 max-h-[calc(100vh-180px)] overflow-y-auto">
                @foreach($lessons as $item)
                <a href="{{ route('lessons.show', [$course, $item]) }}"
                   class="flex items-center gap-3 p-4 hover:bg-gray-50 transition-all {{ $lesson->id == $item->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                    <div class="flex-shrink-0">
                        @php
                            $isCompleted = $item->isCompletedBy(auth()->id());
                        @endphp
                        @if($isCompleted)
                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        @elseif($lesson->id == $item->id)
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                </svg>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <span class="text-xs text-gray-500 font-medium">{{ $loop->iteration }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium {{ $lesson->id == $item->id ? 'text-blue-600' : 'text-gray-800' }} truncate">
                            {{ $item->judul }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <i class="fas fa-{{ $item->tipe == 'video' ? 'video' : ($item->tipe == 'pdf' ? 'file-pdf' : 'file-alt') }} mr-1"></i>
                            {{ ucfirst($item->tipe) }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-8">
            <div class="max-w-4xl mx-auto">
                <!-- Video Section -->
                @if($lesson->tipe == 'video' && $lesson->url_video)
                <div class="bg-black rounded-2xl overflow-hidden shadow-xl mb-6">
                    <video class="w-full" controls>
                        <source src="{{ $lesson->url_video }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                @endif

                <!-- PDF Preview -->
                @if($lesson->tipe == 'pdf' && $lesson->file_pdf)
                <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-pdf text-2xl text-red-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Materi PDF</p>
                                <p class="text-sm text-gray-500">{{ basename($lesson->file_pdf) }}</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($lesson->file_pdf) }}" target="_blank"
                           class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all">
                            <i class="fas fa-eye mr-2"></i> Baca PDF
                        </a>
                    </div>
                </div>
                @endif

                <!-- Material File Download -->
                @if($lesson->material_file)
                <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file text-2xl text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Materi Pembelajaran</p>
                                <p class="text-sm text-gray-500">{{ basename($lesson->material_file) }}</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($lesson->material_file) }}" download
                           class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-all">
                            <i class="fas fa-download mr-2"></i> Download
                        </a>
                    </div>
                </div>
                @endif

                <!-- Lesson Header -->
                <div class="mb-6">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">{{ $lesson->judul }}</h1>
                </div>

                <!-- Content Text -->
                @if($lesson->konten_teks)
                <div class="prose prose-lg max-w-none bg-white rounded-2xl shadow-sm p-6 mb-6">
                    {!! nl2br(e($lesson->konten_teks)) !!}
                </div>
                @endif

                <!-- Navigation & Complete Button -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                    <div>
                        @if($prevLesson)
                        <a href="{{ route('lessons.show', [$course, $prevLesson]) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Previous Lesson
                        </a>
                        @endif
                    </div>

                    @if(!$completion)
                    <button id="completeLessonBtn"
                            data-url="{{ route('lessons.complete', [$course, $lesson]) }}"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mark as Completed
                    </button>
                    @else
                    <div class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-100 text-emerald-700 rounded-xl">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Completed ✓
                    </div>
                    @endif

                    <div>
                        @if($nextLesson)
                        <a href="{{ route('lessons.show', [$course, $nextLesson]) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                            Next Lesson
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('completeLessonBtn')?.addEventListener('click', async function() {
        const btn = this;
        const url = btn.dataset.url;

        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Processing...';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Completed ✓';
                btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
                btn.classList.add('bg-emerald-100', 'text-emerald-700', 'cursor-default');
                btn.disabled = true;

                // Update progress
                const progressBar = document.querySelector('.bg-white/20 .bg-white');
                if (progressBar && data.course_progress) {
                    progressBar.style.width = data.course_progress + '%';
                }

                // Reload page to update sidebar status
                setTimeout(() => location.reload(), 1000);
            }
        } catch (error) {
            btn.disabled = false;
            btn.innerHTML = 'Mark as Completed';
            alert('Error completing lesson. Please try again.');
        }
    });
</script>
@endpush
@endsection
