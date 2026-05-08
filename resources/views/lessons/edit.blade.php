@extends('layouts.app')

@section('title', 'Edit Lesson: ' . $lesson->judul)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-edit mr-3 text-amber-600"></i>Edit Lesson
        </h1>
        <p class="text-sm text-gray-500 mt-1">{{ $lesson->judul }}</p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <h3 class="font-semibold text-red-800 mb-2">❌ Ada Kesalahan pada Form:</h3>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('lessons.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Judul -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Judul Lesson</label>
                <input type="text"
                       name="judul"
                       value="{{ old('judul', $lesson->judul) }}"
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

                    <option value="teks" {{ old('tipe', $lesson->tipe) == 'teks' ? 'selected' : '' }}>Teks (Rich Text)</option>
                    <option value="video" {{ old('tipe', $lesson->tipe) == 'video' ? 'selected' : '' }}>Video (YouTube)</option>
                    <option value="pdf" {{ old('tipe', $lesson->tipe) == 'pdf' ? 'selected' : '' }}>PDF File</option>

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
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('konten_teks') border-red-500 @enderror">{{ old('konten_teks', $lesson->konten_teks) }}</textarea>

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
                       value="{{ old('url_video', $lesson->url_video) }}"
                       placeholder="https://www.youtube.com/watch?v=..."
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('url_video') border-red-500 @enderror">

                <p class="text-xs text-gray-400 mt-1">Masukkan URL YouTube</p>

                @error('url_video')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror

                @if($lesson->url_video)
                    <div class="bg-blue-50 text-blue-700 text-xs p-3 rounded-xl mt-2">
                        Video saat ini: {{ $lesson->url_video }}
                    </div>
                @endif

                <div id="video-preview" class="mt-2"></div>
            </div>

            <!-- PDF -->
            <div id="pdf-content" class="content-type hidden">
                <label class="block text-xs font-medium text-gray-500 mb-1">File PDF</label>

                <input type="file"
                       name="file_pdf"
                       accept=".pdf"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl @error('file_pdf') border-red-500 @enderror">

                <p class="text-xs text-gray-400 mt-1">
                    Kosongkan jika tidak ingin mengubah
                </p>

                @error('file_pdf')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror

                @if($lesson->file_pdf)
                    <div class="bg-gray-50 p-3 rounded-xl text-sm mt-2">
                        <a href="{{ Storage::url($lesson->file_pdf) }}"
                           target="_blank"
                           class="text-blue-600 hover:underline">
                            Lihat PDF Saat Ini
                        </a>

                        <div class="mt-2">
                            <label class="text-red-600 text-xs">
                                <input type="checkbox" name="remove_pdf"> Hapus PDF saat ini
                            </label>
                        </div>
                    </div>
                @endif
            </div>

            <!-- MATERI PEMBELAJARAN (OPSIONAL) -->
            {{-- <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">
                    <i class="fas fa-paperclip mr-1"></i> Materi Pembelajaran (Opsional)
                </label>

                <input type="file"
                       name="material_file"
                       accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl @error('material_file') border-red-500 @enderror">

                <p class="text-xs text-gray-400 mt-1">
                    Kosongkan jika tidak ingin mengubah. Tipe: PDF, Word, PowerPoint, Excel, ZIP, RAR (Max 100MB)
                </p>

                @error('material_file')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror

                @if($lesson->material_file)
                    <div class="bg-gray-50 p-3 rounded-xl text-sm mt-2">
                        <a href="{{ Storage::url($lesson->material_file) }}"
                           target="_blank"
                           class="text-blue-600 hover:underline">
                            <i class="fas fa-download mr-1"></i> Download Materi Saat Ini
                        </a>

                        <div class="mt-2">
                            <label class="text-red-600 text-xs">
                                <input type="checkbox" name="remove_material"> Hapus Materi saat ini
                            </label>
                        </div>
                    </div>
                @endif
            </div> --}}
            <!-- ACTION -->
            <div class="flex justify-between pt-4">
                <a href="{{ route('courses.show', $lesson->course) }}"
                   class="px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200">
                    Batal
                </a>

                <button type="submit"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                    <i class="fas fa-save mr-1"></i> Update Lesson
                </button>
            </div>

        </form>

    </div>

</div>

@push('scripts')
    <script>
    function toggleContentType() {
        const tipe = document.getElementById('tipe').value;

        // Hide all content types first
        document.querySelectorAll('.content-type').forEach(el => {
            el.classList.add('hidden');
        });

        // Show the selected content type
        if (tipe === 'teks') {
            document.getElementById('teks-content').classList.remove('hidden');
        } else if (tipe === 'video') {
            document.getElementById('video-content').classList.remove('hidden');
        } else if (tipe === 'pdf') {
            document.getElementById('pdf-content').classList.remove('hidden');
        }

        // Also show any fields that have validation errors (even if hidden)
        document.querySelectorAll('.content-type').forEach(el => {
            if (el.querySelector('.border-red-500, .text-red-500')) {
                el.classList.remove('hidden');
            }
        });
    }

    // INIT - run once on page load
    toggleContentType();

    // Listen to type changes
    document.getElementById('tipe')?.addEventListener('change', toggleContentType);

    // YouTube Preview
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
        // const regExp = /^.*(youtu.be\\/|v\\/|u\\/\\w\\/|embed\\/|watch\\?v=|&v=)([^#&?]*).*/;
        const regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }
    </script>
@endpush

@endsection
