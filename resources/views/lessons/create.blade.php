@extends('layouts.app')

@section('title', 'Tambah Lesson Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-plus-circle mr-3 text-blue-600"></i>Tambah Lesson Baru
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Tambahkan materi pembelajaran ke dalam kursus
        </p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <form action="{{ route('lessons.store', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Judul -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Judul Lesson</label>
                <input type="text"
                       name="judul"
                       value="{{ old('judul') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('judul') border-red-500 @enderror"
                       required>

                @error('judul')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tipe Materi</label>
                <select name="tipe"
                        id="tipe"
                        onchange="toggleContentType()"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('tipe') border-red-500 @enderror"
                        required>

                    <option value="teks" {{ old('tipe') == 'teks' ? 'selected' : '' }}>Teks</option>
                    <option value="video" {{ old('tipe') == 'video' ? 'selected' : '' }}>Video</option>
                    <option value="pdf" {{ old('tipe') == 'pdf' ? 'selected' : '' }}>PDF</option>

                </select>

                @error('tipe')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- TEKS -->
            <div id="teks-content" class="content-type hidden">
                <label class="block text-xs font-medium text-gray-500 mb-1">Konten Teks</label>

                <textarea name="konten_teks"
                          rows="8"
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('konten_teks') border-red-500 @enderror">{{ old('konten_teks') }}</textarea>

                <p class="text-xs text-gray-400 mt-1">Bisa pakai HTML</p>

                @error('konten_teks')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- VIDEO -->
            <div id="video-content" class="content-type hidden">
                <label class="block text-xs font-medium text-gray-500 mb-1">URL YouTube</label>

                <input type="url"
                       id="url_video"
                       name="url_video"
                       value="{{ old('url_video') }}"
                       placeholder="https://www.youtube.com/watch?v=..."
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('url_video') border-red-500 @enderror">

                <p class="text-xs text-gray-400 mt-1">Masukkan URL YouTube</p>

                @error('url_video')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <div id="video-preview" class="mt-2"></div>
            </div>

            <!-- PDF -->
            <div id="pdf-content" class="content-type hidden">
                <label class="block text-xs font-medium text-gray-500 mb-1">Upload PDF</label>

                <input type="file"
                       name="file_pdf"
                       accept=".pdf"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl @error('file_pdf') border-red-500 @enderror">

                <p class="text-xs text-gray-400 mt-1">Max 50MB</p>

                @error('file_pdf')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ACTION -->
            <div class="flex justify-between pt-4">

                <a href="{{ route('courses.show', $course) }}"
                   class="px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200">
                    Batal
                </a>

                <button type="submit"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                    <i class="fas fa-save mr-1"></i> Simpan Lesson
                </button>

            </div>

        </form>

    </div>

</div>

@push('scripts')
<script>
function toggleContentType() {
    const tipe = document.getElementById('tipe').value;

    document.querySelectorAll('.content-type').forEach(el => {
        el.classList.add('hidden');
    });

    if (tipe === 'teks') {
        document.getElementById('teks-content').classList.remove('hidden');
    } else if (tipe === 'video') {
        document.getElementById('video-content').classList.remove('hidden');
    } else if (tipe === 'pdf') {
        document.getElementById('pdf-content').classList.remove('hidden');
    }
}

// INIT
toggleContentType();

// YOUTUBE PREVIEW
document.getElementById('url_video')?.addEventListener('input', function() {
    const url = this.value;
    const videoId = extractYouTubeId(url);
    const preview = document.getElementById('video-preview');

    if (videoId) {
        preview.innerHTML = `
            <div class="bg-blue-50 text-blue-700 text-xs p-3 rounded-xl">
                Preview: https://www.youtube.com/watch?v=${videoId}
            </div>
        `;
    } else {
        preview.innerHTML = '';
    }
});

function extractYouTubeId(url) {
    const regExp = /^.*(youtu.be\\/|v\\/|u\\/\\w\\/|embed\\/|watch\\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}
</script>
@endpush

@endsection
