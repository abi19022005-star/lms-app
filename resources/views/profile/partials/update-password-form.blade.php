<div class="space-y-6">

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Current Password -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">
                Password Saat Ini
            </label>

            <div class="relative">
                <input type="password"
                       id="current_password"
                       name="current_password"
                       autocomplete="current-password"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 pr-10 @error('current_password') border-red-500 @enderror"
                       required>

                <button type="button"
                        onclick="togglePassword('current_password', this)"
                        class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            @error('current_password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">
                Password Baru
            </label>

            <div class="relative">
                <input type="password"
                       id="password"
                       name="password"
                       autocomplete="new-password"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 pr-10 @error('password') border-red-500 @enderror"
                       required>

                <button type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter</p>

            @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">
                Konfirmasi Password Baru
            </label>

            <div class="relative">
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       autocomplete="new-password"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 pr-10"
                       required>

                <button type="button"
                        onclick="togglePassword('password_confirmation', this)"
                        class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm rounded-xl hover:bg-amber-600 transition">
                <i class="fas fa-key"></i> Update Password
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
