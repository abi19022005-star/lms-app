<x-guest-layout>

<div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-8">

    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Buat Akun</h1>
        <p class="text-gray-500">Mulai belajar sekarang</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <input type="text" name="name" placeholder="Nama"
               class="w-full px-4 py-3 rounded-xl border">

        <input type="email" name="email" placeholder="Email"
               class="w-full px-4 py-3 rounded-xl border">

        <input type="password" name="password" placeholder="Password" autocomplete="new-password"
               class="w-full px-4 py-3 rounded-xl border">

        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" autocomplete="new-password"
               class="w-full px-4 py-3 rounded-xl border">

        <button class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full shadow-lg">
            Register
        </button>
        <!-- Register -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-600 font-semibold">Masuk</a>
        </p>
    </form>

</div>

</x-guest-layout>
