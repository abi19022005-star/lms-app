@extends('layouts.app')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-user-edit mr-3 text-amber-600"></i>Edit User
            </h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui data pengguna</p>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-xl hover:bg-gray-200">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Nama -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full mt-1 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full mt-1 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Password Baru</label>
                    <div class="relative mt-1">
                        <input type="password" id="password" name="password" autocomplete="new-password"
                            class="w-full px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500">
                        <button type="button" onclick="togglePassword('password')"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak diubah</p>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Konfirmasi Password</label>
                    <div class="relative mt-1">
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                            class="w-full px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500">
                        <button type="button" onclick="togglePassword('password_confirmation')"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Role -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Role *</label>
                    <select name="role"
                        class="w-full mt-1 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500">
                        <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Upload Foto -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Foto Baru</label>
                    <input type="file" name="foto" accept="image/*" onchange="previewImage(event)"
                        class="w-full mt-1 text-sm border border-gray-200 rounded-xl file:px-4 file:py-2 file:border-0 file:bg-amber-600 file:text-white file:rounded-lg">
                </div>
            </div>

            <!-- BIO -->
            <div>
                <label class="text-sm font-medium text-gray-600">Bio</label>
                <textarea name="bio" rows="3"
                    class="w-full mt-1 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <!-- FOTO SAAT INI -->
            @if($user->foto)
            <div>
                <label class="text-sm font-medium text-gray-600">Foto Saat Ini</label>
                <div class="flex items-center gap-4 mt-2">
                    <img src="{{ Storage::url($user->foto) }}"
                        class="w-20 h-20 rounded-xl object-cover border">

                    <label class="flex items-center gap-2 text-sm text-red-600">
                        <input type="checkbox" name="remove_foto" class="rounded">
                        Hapus foto
                    </label>
                </div>
            </div>
            @endif

            <!-- PREVIEW FOTO BARU -->
            <div id="imagePreview" class="hidden">
                <label class="text-sm font-medium text-gray-600">Preview Foto Baru</label>
                <img id="preview" class="w-24 h-24 mt-2 rounded-xl object-cover border">
            </div>

            <!-- STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t">
                <div class="bg-blue-50 rounded-xl p-4 text-center">
                    <p class="text-lg font-bold text-blue-700">{{ $user->enrollments_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Kursus</p>
                </div>
                <div class="bg-emerald-50 rounded-xl p-4 text-center">
                    <p class="text-lg font-bold text-emerald-700">{{ $user->certificates_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Sertifikat</p>
                </div>
                <div class="bg-gray-100 rounded-xl p-4 text-center">
                    <p class="text-sm font-semibold text-gray-700">{{ $user->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">Bergabung</p>
                </div>
            </div>

            <!-- ACTION -->
            <div class="flex justify-between items-center pt-4 border-t">

                <!-- Delete -->
                @if($user->id != auth()->id())
                <button type="button" onclick="openDeleteModal()"
                    class="px-4 py-2 text-sm text-white bg-red-600 rounded-xl hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
                @endif

                <!-- Save -->
                <div class="flex gap-3">
                    <a href="{{ route('admin.users.index') }}"
                       class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-amber-600 rounded-xl hover:bg-amber-700">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

<!-- DELETE MODAL -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="bg-red-600 text-white px-6 py-4 rounded-t-2xl">
            <h5 class="font-semibold">
                <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus
            </h5>
        </div>
        <div class="p-6">
            <p>Yakin hapus user <strong>{{ $user->name }}</strong>?</p>
            <p class="text-red-600 text-sm mt-2">Tidak bisa dibatalkan!</p>

            <form id="deleteForm" action="{{ route('admin.users.destroy', $user) }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div>
        <div class="flex justify-end gap-3 px-6 py-4 border-t">
            <button onclick="closeDeleteModal()"
                class="px-4 py-2 text-sm bg-gray-100 rounded-xl">Batal</button>
            <button type="submit" form="deleteForm"
                class="px-4 py-2 text-sm text-white bg-red-600 rounded-xl">Hapus</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function previewImage(event) {
    const preview = document.getElementById('preview');
    const container = document.getElementById('imagePreview');

    const file = event.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
}

function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
</script>
@endpush

@endsection
