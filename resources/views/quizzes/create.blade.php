@extends('layouts.app')

@section('title', 'Buat Kuis Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-plus-circle mr-3 text-blue-600"></i>Buat Kuis Baru
        </h1>
        <p class="text-sm text-gray-500 mt-1">Tambahkan kuis baru untuk mengecek pemahaman siswa</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('quizzes.store', $course) }}" method="POST" class="space-y-5">
            @csrf

            <!-- Judul -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Judul Kuis</label>
                <input type="text"
                       id="judul"
                       name="judul"
                       value="{{ old('judul') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('judul') border-red-500 @enderror"
                       required>
                @error('judul')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Passing Score -->
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Passing Score (%)</label>
                <input type="number"
                       id="passing_score"
                       name="passing_score"
                       value="{{ old('passing_score', 70) }}"
                       min="0"
                       max="100"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('passing_score') border-red-500 @enderror"
                       required>
                <p class="text-xs text-gray-400 mt-1">Nilai minimal untuk lulus (default: 70)</p>
                @error('passing_score')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Alert -->
            <div class="bg-blue-50 text-blue-700 text-xs p-3 rounded-xl">
                <i class="fas fa-info-circle mr-2"></i>
                Setelah kuis dibuat, Anda dapat menambahkan soal-soal melalui halaman edit kuis.
            </div>

            <!-- Actions -->
            <div class="flex justify-between pt-4">
                <a href="{{ route('courses.show', $course) }}"
                   class="px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                    <i class="fas fa-save mr-1"></i> Simpan Kuis
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
