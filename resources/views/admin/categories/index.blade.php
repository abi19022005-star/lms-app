@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-tags mr-3 text-blue-600"></i>Manajemen Kategori
            </h1>
            <p class="text-sm text-gray-500 mt-1">Kelola kategori kursus</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </a>
    </div>

    <!-- Categories Grid -->
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($categories as $category)
        <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="p-5">
                <!-- Icon & Title -->
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-400 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                        </svg>
                    </div>
                    <span class="inline-flex px-2 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded-lg">
                        {{ $category->courses_count }} Kursus
                    </span>
                </div>

                <h3 class="text-lg font-semibold text-gray-800">{{ $category->nama }}</h3>
                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $category->description ?? 'Tidak ada deskripsi' }}</p>

                <!-- Slug -->
                <div class="mt-3">
                    <code class="text-xs bg-gray-100 px-2 py-1 rounded-lg text-gray-600">{{ $category->slug }}</code>
                </div>

                <!-- Meta Info -->
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div class="text-xs text-gray-400">
                        <i class="far fa-calendar-alt mr-1"></i> {{ $category->created_at->format('d M Y') }}
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="inline-flex items-center justify-center w-8 h-8 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                        <button type="button" onclick="deleteCategory({{ $category->id }}, '{{ $category->nama }}', {{ $category->courses_count }})"
                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
            </svg>
            <h5 class="mt-4 text-lg font-medium text-gray-700">Belum ada kategori</h5>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kategori Pertama
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>

<!-- Delete Modal -->
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" id="deleteModal">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="bg-red-600 rounded-t-2xl px-6 py-4">
            <h5 class="text-lg font-semibold text-white">Konfirmasi Hapus Kategori</h5>
        </div>
        <div class="p-6">
            <p>Apakah Anda yakin ingin menghapus kategori <strong id="categoryName"></strong>?</p>
            <p id="warningMessage" class="text-red-600 text-sm mt-2"></p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div>
        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
            <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
            <button type="submit" form="deleteForm" class="px-4 py-2 text-sm text-white bg-red-600 rounded-xl hover:bg-red-700" id="deleteBtn">Hapus</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function deleteCategory(id, name, courseCount) {
        document.getElementById('categoryName').textContent = name;
        const warningMsg = document.getElementById('warningMessage');
        const deleteBtn = document.getElementById('deleteBtn');

        if (courseCount > 0) {
            warningMsg.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>Kategori ini memiliki ${courseCount} kursus. Tidak dapat dihapus!`;
            deleteBtn.disabled = true;
        } else {
            warningMsg.innerHTML = '';
            deleteBtn.disabled = false;
        }

        document.getElementById('deleteForm').action = '/admin/categories/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
