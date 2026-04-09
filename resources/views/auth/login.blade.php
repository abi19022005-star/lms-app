<x-guest-layout>

<div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-8">

    <!-- Title -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Masuk</h1>
        <p class="text-gray-500 mt-1"> ke akun Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label class="text-sm text-gray-600">Email</label>
            <div class="relative mt-1">
                <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full pl-10 pr-3 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500"
                       placeholder="Masukkan email">
            </div>
        </div>

        <!-- Password -->
        <div>
            <label class="text-sm text-gray-600">Password</label>
            <div class="relative mt-1">
                <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                <input type="password" name="password" autocomplete="current-password"
                       class="w-full pl-10 pr-3 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500"
                       placeholder="Masukkan password">
            </div>
        </div>

        <!-- Options -->
        <div class="flex justify-between text-sm">
            <label class="flex items-center gap-2 text-gray-600">
                <input type="checkbox" name="remember"> Remember
            </label>

            <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                Lupa password?
            </a>
        </div>

        <!-- Button -->
        <button class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-full hover:scale-[1.02] transition shadow-lg">
            Login
        </button>
    </form>

    <!-- Register -->
    <p class="text-center text-sm text-gray-500 mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-blue-600 font-semibold">Daftar</a>
    </p>

</div>

</x-guest-layout>
