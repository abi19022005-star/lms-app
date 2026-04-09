<x-guest-layout>

<div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-8">

    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Reset Password 🔑</h1>
        <p class="text-gray-500">Masukkan email Anda</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <input type="email" name="email" placeholder="Email"
               class="w-full px-4 py-3 rounded-xl border">

        <button class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full">
            Kirim Link Reset
        </button>
    </form>

</div>

</x-guest-layout>
