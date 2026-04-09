@extends('layouts.app')

@section('title', 'Detail User: ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-user-circle mr-3 text-blue-600"></i>{{ $user->name }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">Detail lengkap profil user</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-xl hover:bg-amber-600 transition-all">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left Column - Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-4 text-center">
                    <div class="relative inline-block">
                        @if($user->foto)
                            <img src="{{ Storage::url($user->foto) }}" alt="{{ $user->name }}"
                                 class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-28 h-28 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white shadow-lg">
                                <i class="fas fa-user text-5xl text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="text-white font-bold text-xl mt-3">{{ $user->name }}</h5>
                    <p class="text-blue-100 text-sm">{{ $user->email }}</p>
                    <div class="mt-2">
                        @if($user->role == 'admin')
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-red-500/20 text-white">
                                <i class="fas fa-shield-alt mr-1"></i> Admin
                            </span>
                        @elseif($user->role == 'guru')
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-amber-500/20 text-white">
                                <i class="fas fa-chalkboard-teacher mr-1"></i> Guru
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-500/20 text-white">
                                <i class="fas fa-user-graduate mr-1"></i> Siswa
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        @if($user->banned_at)
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700">
                                <i class="fas fa-ban mr-1"></i> Dilarang
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-emerald-100 text-emerald-700">
                                <i class="fas fa-check-circle mr-1"></i> Aktif
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Email</span>
                        @if($user->email_verified_at)
                            <span class="text-xs text-emerald-600"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                        @else
                            <span class="text-xs text-amber-600"><i class="fas fa-exclamation-circle"></i> Belum Verifikasi</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h6 class="text-sm font-semibold text-gray-700"><i class="fas fa-cog mr-2"></i> Aksi Cepat</h6>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="flex items-center gap-3 w-full px-3 py-2 text-sm text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                        <i class="fas fa-edit w-4 text-gray-400"></i> Edit Profil
                    </a>

                    @if(!$user->banned_at)
                        <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" onclick="return confirm('Yakin ingin melarang user ini?')"
                                    class="flex items-center gap-3 w-full px-3 py-2 text-sm text-red-600 rounded-xl hover:bg-red-50 transition-colors">
                                <i class="fas fa-ban w-4 text-red-400"></i> Larang User
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-3 w-full px-3 py-2 text-sm text-emerald-600 rounded-xl hover:bg-emerald-50 transition-colors">
                                <i class="fas fa-check-circle w-4 text-emerald-400"></i> Buka Larangan
                            </button>
                        </form>
                    @endif

                    @if(auth()->user()->id !== $user->id)
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="w-full" id="deleteUserForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete()"
                                    class="flex items-center gap-3 w-full px-3 py-2 text-sm text-red-600 rounded-xl hover:bg-red-50 transition-colors">
                                <i class="fas fa-trash w-4 text-red-400"></i> Hapus User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Account Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                    <h6 class="text-sm font-semibold text-gray-700"><i class="fas fa-info-circle mr-2"></i> Informasi Akun</h6>
                </div>
                <div class="p-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Nama Lengkap</p>
                            <p class="text-gray-800 font-medium mt-1">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Email</p>
                            <p class="text-gray-800 font-medium mt-1">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Role</p>
                            <p class="mt-1">
                                @if($user->role == 'admin')
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700">Admin</span>
                                @elseif($user->role == 'guru')
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-amber-100 text-amber-700">Guru</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700">Siswa</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            {{-- <p class="text-xs text-gray-400 uppercase tracking-wide">User ID</p> --}}
                            <p class="text-xs text-gray-400 uppercase tracking-wide">User ID {{ $user->role }}</p>
                            {{-- <p class="text-gray-800 font-medium mt-1">#{{ $user->id }}</p> --}}
                            <p class="text-gray-800 font-medium mt-1">
                                {{ $user->role === 'siswa' ? ' '.$user->nis : ' '.$user->nip }}
                            </p>
                        </div>
                    </div>
                    @if($user->bio)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Bio</p>
                            <p class="text-gray-600 mt-1">{{ $user->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-book text-blue-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $user->enrollments_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Kursus Diambil</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-certificate text-emerald-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $user->certificates_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Sertifikat</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-calendar text-amber-600"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-800">{{ $user->created_at->format('d/m/Y') }}</p>
                    <p class="text-xs text-gray-500">Bergabung</p>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                    <h6 class="text-sm font-semibold text-gray-700"><i class="fas fa-history mr-2"></i> Timeline</h6>
                </div>
                <div class="p-5">
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-plus-circle text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Akun dibuat</p>
                                <p class="text-sm font-medium text-gray-800">{{ $user->created_at->format('d F Y H:i') }}</p>
                                <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-edit text-amber-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Akun diperbarui</p>
                                <p class="text-sm font-medium text-gray-800">{{ $user->updated_at->format('d F Y H:i') }}</p>
                                <p class="text-xs text-gray-400">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if($user->email_verified_at)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Email terverifikasi</p>
                                <p class="text-sm font-medium text-gray-800">{{ $user->email_verified_at->format('d F Y H:i') }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email_verified_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                        @if($user->banned_at)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-ban text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">User dilarang</p>
                                <p class="text-sm font-medium text-gray-800">{{ $user->banned_at->format('d F Y H:i') }}</p>
                                <p class="text-xs text-gray-400">{{ $user->banned_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" id="deleteModal">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="bg-red-600 rounded-t-2xl px-6 py-4">
            <h5 class="text-lg font-semibold text-white"><i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Hapus User</h5>
        </div>
        <div class="p-6">
            <p>Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>?</p>
            <p class="text-red-600 text-sm mt-2">Tindakan ini tidak dapat dibatalkan! Semua data terkait user ini akan dihapus.</p>
        </div>
        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
            <button type="submit" form="deleteUserForm" class="px-4 py-2 text-sm text-white bg-red-600 rounded-xl hover:bg-red-700">Hapus User</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete() {
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
