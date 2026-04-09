@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-users mr-3 text-blue-600"></i>Manajemen User
            </h1>
            <p class="text-sm text-gray-500 mt-1">Kelola semua pengguna sistem e-learning</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah User Baru
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" id="filterForm" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari nama atau email</label>
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Cari...">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Role</label>
                <select name="role" id="roleFilter" class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Sortir</label>
                <select name="sort" id="sortFilter" class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-all">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
            <div>
                <button type="button" onclick="exportUsers()" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition-all">
                    <i class="fas fa-file-excel mr-2"></i> Export
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h6 class="text-sm font-semibold text-gray-700">
                    <i class="fas fa-list mr-2"></i> Daftar User
                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $users->total() }} Total</span>
                </h6>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Bergabung</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Kursus</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Sertifikat</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-sm text-gray-500">{{ $users->firstItem() + $index }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if($user->foto)
                                    <img src="{{ Storage::url($user->foto) }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $user->role === 'siswa' ? 'NIS: '.$user->nis : 'NIP: '.$user->nip }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-5 py-3">
                            @if($user->role == 'admin')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700"><i class="fas fa-shield-alt mr-1"></i> Admin</span>
                            @elseif($user->role == 'guru')
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-amber-100 text-amber-700"><i class="fas fa-chalkboard-teacher mr-1"></i> Guru</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700"><i class="fas fa-user-graduate mr-1"></i> Siswa</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700">{{ $user->enrollments_count ?? 0 }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-emerald-100 text-emerald-700">{{ $user->certificates_count ?? 0 }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="w-8 h-8 flex items-center justify-center text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <button type="button" onclick="viewUser({{ $user->id }})" class="w-8 h-8 flex items-center justify-center text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors" title="Lihat Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                                @if($user->id != auth()->id())
                                <button type="button" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" class="w-8 h-8 flex items-center justify-center text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors" title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <h5 class="mt-4 text-lg font-medium text-gray-700">Tidak ada data user</h5>
                            <p class="text-gray-400">Belum ada user yang terdaftar</p>
                            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah User Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
            <div class="text-xs text-gray-500">
                Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari {{ $users->total() }} data
            </div>
            <div>
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" id="deleteUserModal">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="bg-red-600 rounded-t-2xl px-6 py-4">
            <h5 class="text-lg font-semibold text-white"><i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Hapus User</h5>
        </div>
        <div class="p-6">
            <p>Apakah Anda yakin ingin menghapus user <strong id="deleteUserName"></strong>?</p>
            <p class="text-red-600 text-sm mt-2">Tindakan ini tidak dapat dibatalkan!</p>
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div>
        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
            <button type="submit" form="deleteUserForm" class="px-4 py-2 text-sm text-white bg-red-600 rounded-xl hover:bg-red-700">Hapus User</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let searchTimeout;

    // Auto-submit ketika role atau sort berubah
    document.getElementById('roleFilter')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('sortFilter')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Auto-submit search dengan debouncing (300ms delay)
    document.getElementById('searchInput')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 300);
    });

    function deleteUser(id, name) {
        document.getElementById('deleteUserName').textContent = name;
        document.getElementById('deleteUserForm').action = '/admin/users/' + id;
        document.getElementById('deleteUserModal').classList.remove('hidden');
        document.getElementById('deleteUserModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteUserModal').classList.add('hidden');
        document.getElementById('deleteUserModal').classList.remove('flex');
    }

    function viewUser(id) {
        window.location.href = '/admin/users/' + id;
    }

    function exportUsers() {
        window.location.href = '{{ route("admin.users.export") }}' + window.location.search;
    }
</script>
@endpush
@endsection
