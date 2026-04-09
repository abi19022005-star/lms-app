@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-user-circle mr-3 text-blue-600"></i>Edit Profile
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Kelola informasi akun dan keamanan Anda
        </p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

        <!-- LEFT: PROFILE CARD -->
        <div class="lg:col-span-1 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-4 text-center">

                    <div class="relative inline-block">
                        @if(auth()->user()->foto)
                            <img src="{{ Storage::url(auth()->user()->foto) }}"
                                 class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-28 h-28 rounded-full bg-white/20 flex items-center justify-center border-4 border-white shadow-lg">
                                <i class="fas fa-user text-5xl text-white"></i>
                            </div>
                        @endif
                    </div>

                    <h5 class="text-white font-bold text-xl mt-3">
                        {{ auth()->user()->name }}
                    </h5>

                    <p class="text-blue-100 text-sm">
                        {{ auth()->user()->email }}
                    </p>

                    <!-- Role -->
                    <div class="mt-2">
                        @if(auth()->user()->role == 'admin')
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-red-500/20 text-white">
                                Admin
                            </span>
                        @elseif(auth()->user()->role == 'guru')
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-amber-500/20 text-white">
                                Guru
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-500/20 text-white">
                                Siswa
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Info -->
                <div class="p-5 space-y-3">

                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="text-xs text-emerald-600">Aktif</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">User ID</span>
                        <span class="text-xs text-gray-800">
                            {{ auth()->user()->role === 'siswa' ? auth()->user()->nis : auth()->user()->nip }}
                        </span>
                    </div>

                </div>
            </div>
            <!-- Update Password -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50">
                    <h6 class="text-sm font-semibold text-gray-700">
                        Ubah Password
                    </h6>
                </div>

                <div class="p-5">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- RIGHT: FORM -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Update Profile -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50">
                    <h6 class="text-sm font-semibold text-gray-700">
                        Informasi Profil
                    </h6>
                </div>

                <div class="p-5">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>



            <!-- Delete Account -->
            @if(auth()->user()->isSiswa())
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
                <div class="px-5 py-4 border-b bg-red-50">
                    <h6 class="text-sm font-semibold text-red-600">
                        Hapus Akun
                    </h6>
                </div>

                <div class="p-5">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endif

        </div>

    </div>
</div>
@endsection
