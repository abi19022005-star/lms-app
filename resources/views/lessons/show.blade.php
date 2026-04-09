@extends('layouts.app')

@section('title', $lesson->judul)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('courses.show', $lesson->course) }}"
           class="inline-flex items-center px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Kursus
        </a>
        <h1 class="text-2xl font-bold text-gray-800 flex-1 mx-4">{{ $lesson->judul }}</h1>
        @if(auth()->user()->isSiswa() && !$lesson->isCompletedByUser(auth()->id()))
            <button class="inline-flex items-center px-4 py-2 text-sm bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 complete-lesson" data-lesson-id="{{ $lesson->id }}">
                <i class="fas fa-check mr-2"></i> Tandai Selesai
            </button>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm bg-emerald-100 text-emerald-700 rounded-xl font-semibold">
                <i class="fas fa-check-circle mr-2"></i> Selesai
            </span>
        @endif
    </div>

    <!-- Lesson Content Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @if($lesson->tipe == 'teks')
            <div class="prose prose-sm max-w-none">
                {!! $lesson->konten_teks !!}
            </div>

        @elseif($lesson->tipe == 'video')
            @php
                $videoId = null;
                preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $lesson->url_video, $matches);
                $videoId = $matches[1] ?? null;
            @endphp
            @if($videoId)
                <div class="aspect-video rounded-xl overflow-hidden bg-black">
                    <iframe width="100%"
                            height="100%"
                            src="https://www.youtube.com/embed/{{ $videoId }}"
                            title="{{ $lesson->judul }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            class="w-full h-full"></iframe>
                </div>
            @else
                <div class="bg-red-50 text-red-700 text-sm p-6 rounded-xl border border-red-200">
                    <p class="flex items-center gap-2 font-semibold mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Video tidak dapat dimuat</span>
                    </p>
                    <p class="text-xs mb-4">Pastikan URL YouTube yang Anda masukkan valid. Coba buka langsung di YouTube:</p>
                    <a href="{{ $lesson->url_video }}" target="_blank" class="inline-flex items-center px-4 py-2 text-sm bg-red-600 text-white rounded-xl hover:bg-red-700">
                        <i class="fas fa-external-link-alt mr-2"></i> Buka di YouTube
                    </a>
                </div>
            @endif

        @elseif($lesson->tipe == 'pdf')
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-file-pdf text-4xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $lesson->judul }}</h3>
                <p class="text-sm text-gray-600 mb-4">Tekan tombol di bawah untuk membuka dokumen PDF</p>
                <a href="{{ Storage::url($lesson->file_pdf) }}"
                   target="_blank"
                   class="inline-flex items-center px-6 py-3 text-sm bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold">
                    <i class="fas fa-download mr-2"></i> Buka PDF
                </a>
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.complete-lesson').click(function() {
        var button = $(this);
        var lessonId = button.data('lesson-id');

        $.ajax({
            url: '/lessons/' + lessonId + '/complete',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');
            },
            success: function(response) {
                if (response.success) {
                    button.replaceWith('<span class="inline-flex items-center px-4 py-2 text-sm bg-emerald-100 text-emerald-700 rounded-xl font-semibold"><i class="fas fa-check-circle mr-2"></i> Selesai</span>');

                    if (response.is_completed) {
                        setTimeout(function() {
                            window.location.href = '{{ route("courses.show", $lesson->course) }}';
                        }, 2000);
                    }
                }
            },
            error: function() {
                button.prop('disabled', false).html('<i class="fas fa-check mr-2"></i> Tandai Selesai');
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });
});
</script>
@endpush
@endsection
