<div class="space-y-6">

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PATCH')

        <!-- Nama -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Lengkap</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', auth()->user()->name) }}"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                   required>

            @error('name')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', auth()->user()->email) }}"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                   required>

            @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bio -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Bio</label>
            <textarea name="bio"
                      rows="3"
                      class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 @error('bio') border-red-500 @enderror">{{ old('bio', auth()->user()->bio) }}</textarea>

            <p class="text-xs text-gray-400 mt-1">Ceritakan sedikit tentang diri Anda</p>

            @error('bio')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Foto -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-2">Foto Profil</label>

            <div class="flex items-center gap-4">

                <!-- Preview -->
                @if(auth()->user()->foto)
                    <img src="{{ Storage::url(auth()->user()->foto) }}"
                         class="w-20 h-20 rounded-full object-cover border">
                @else
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                @endif

                <!-- Upload -->
                <div class="flex-1">
                    <input type="file"
                           name="foto"
                           accept="image/*"
                           class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 @error('foto') border-red-500 @enderror">

                    <p class="text-xs text-gray-400 mt-1">
                        JPG, PNG, GIF (max 2MB)
                    </p>
                </div>

            </div>

            @error('foto')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div class="pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700 transition">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>

    </form>

</div>
