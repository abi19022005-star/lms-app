@extends('layouts.app')

@section('title', 'Buat Kursus Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-plus-circle mr-3 text-blue-600"></i>Buat Kursus Baru
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Tambahkan kursus baru untuk dibagikan kepada siswa
        </p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Judul -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Judul Kursus</label>
                <input type="text"
                       name="judul"
                       value="{{ old('judul') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('judul') border-red-500 @enderror"
                       required>

                @error('judul')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Deskripsi</label>
                <textarea name="deskripsi"
                          rows="5"
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror"
                          required>{{ old('deskripsi') }}</textarea>

                @error('deskripsi')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                    <select name="kategori_id"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('kategori_id') border-red-500 @enderror"
                            required>

                        <option value="">Pilih Kategori</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('kategori_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach

                    </select>

                    @error('kategori_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Harga</label>

                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden @error('harga') border-red-500 @enderror">
                        <span class="px-3 text-sm text-gray-500">Rp</span>
                        <input type="number"
                               name="harga"
                               value="{{ old('harga', 0) }}"
                               min="0"
                               step="1000"
                               class="w-full px-3 py-2 text-sm focus:outline-none"
                               required>
                    </div>

                    <p class="text-xs text-gray-400 mt-1">Isi 0 untuk kursus gratis</p>

                    @error('harga')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                        required>

                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                        Published
                    </option>

                </select>

                <p class="text-xs text-gray-400 mt-1">
                    Draft hanya bisa dilihat oleh Anda
                </p>

                @error('status')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Thumbnail -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Thumbnail</label>

                <input type="file"
                       name="thumbnail"
                       accept="image/*"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl @error('thumbnail') border-red-500 @enderror">

                <p class="text-xs text-gray-400 mt-1">Max 2MB</p>

                @error('thumbnail')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action -->
            <div class="flex justify-between pt-4">

                <a href="{{ route('courses.index') }}"
                   class="px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200">
                    Batal
                </a>

                <button type="submit"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                    <i class="fas fa-save mr-1"></i> Simpan Kursus
                </button>

            </div>

        </form>

    </div>

</div>
@endsection
