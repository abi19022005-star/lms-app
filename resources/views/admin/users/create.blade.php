@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-user-plus mr-3 text-blue-600"></i>Tambah User Baru
            </h1>
            <p class="text-sm text-gray-500 mt-1">Tambahkan pengguna baru ke sistem</p>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-xl hover:bg-gray-200">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Nama -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full mt-1 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan nama">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full mt-1 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan email">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Password *</label>
                    <div class="relative mt-1">
                        <input type="password" id="password" name="password" autocomplete="new-password"
                            class="w-full px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                            placeholder="Minimal 8 karakter">
                        <button type="button" onclick="togglePassword('password')"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Konfirmasi Password *</label>
                    <div class="relative mt-1">
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                            class="w-full px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
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
                        class="w-full mt-1 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Role</option>
                        <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto -->
                <div>
                    <label class="text-sm font-medium text-gray-600">Foto Profil</label>
                    <input type="file" name="foto" accept="image/*" onchange="previewImage(event)"
                        class="w-full mt-1 text-sm border border-gray-200 rounded-xl file:px-4 file:py-2 file:border-0 file:bg-blue-600 file:text-white file:rounded-lg">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, max 2MB</p>
                    @error('foto')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- BIO -->
            <div>
                <label class="text-sm font-medium text-gray-600">Bio</label>
                <textarea name="bio" rows="3"
                    class="w-full mt-1 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                    placeholder="Ceritakan tentang user">{{ old('bio') }}</textarea>
            </div>

            <!-- PREVIEW FOTO -->
            <div id="imagePreview" class="hidden">
                <label class="text-sm font-medium text-gray-600">Preview Foto</label>
                <div class="mt-2">
                    <img id="preview" class="w-24 h-24 rounded-xl object-cover border">
                </div>
            </div>

            <!-- ACTION -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200">
                    Batal
                </a>

                <button type="submit"
                    class="px-4 py-2 text-sm text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm">
                    <i class="fas fa-save mr-2"></i>Simpan User
                </button>
            </div>

        </form>
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
</script>
@endpush

@endsection
