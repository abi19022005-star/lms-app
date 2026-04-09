@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-lg font-semibold text-white">
                        <i class="fas fa-plus mr-2"></i> Tambah Kategori Baru
                    </h5>
                    <p class="text-blue-100 text-sm mt-1">Buat kategori baru untuk kursus</p>
                </div>
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/20 text-white text-sm rounded-xl hover:bg-white/30 transition-all">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-5">
                <!-- Nama Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('nama') border-red-500 ring-2 ring-red-200 @enderror"
                           placeholder="Contoh: Programming, Design, Marketing" autofocus>
                    <p class="text-xs text-gray-400 mt-1">Nama kategori yang akan ditampilkan</p>
                    @error('nama')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('description') border-red-500 ring-2 ring-red-200 @enderror"
                              placeholder="Deskripsikan kategori ini secara singkat...">{{ old('description') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Deskripsi singkat tentang kategori ini (opsional)</p>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Alert -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex gap-3">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                        <div class="text-sm text-blue-700">
                            <p>Slug akan dibuat otomatis dari nama kategori.</p>
                            <p class="text-xs text-blue-600 mt-1">Contoh: "Programming Dasar" → "programming-dasar"</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-gray-100">
                <a href="{{ route('admin.categories.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-sm hover:shadow-md">
                    <i class="fas fa-save mr-2"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
