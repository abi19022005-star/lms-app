@extends('layouts.app')

@section('title', 'Laporan User')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-users mr-3 text-blue-600"></i>Laporan User
            </h1>
            <p class="text-sm text-gray-500 mt-1">Statistik dan data pengguna sistem</p>
        </div>
        <a href="{{ route('admin.reports.export', ['type' => 'users']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm rounded-xl hover:bg-emerald-700">
            <i class="fas fa-file-excel"></i>
            <span>Export CSV</span>
        </a>
    </div>

    <!-- Role Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-red-600 to-red-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-red-100">Admin</p>
                    <p class="text-3xl font-bold mt-2">{{ $roleStats['admin'] }}</p>
                </div>
                <i class="fas fa-shield-alt text-3xl text-white/30"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-600 to-amber-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-amber-100">Guru</p>
                    <p class="text-3xl font-bold mt-2">{{ $roleStats['guru'] }}</p>
                </div>
                <i class="fas fa-chalkboard-user text-3xl text-white/30"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-blue-100">Siswa</p>
                    <p class="text-3xl font-bold mt-2">{{ $roleStats['siswa'] }}</p>
                </div>
                <i class="fas fa-user-graduate text-3xl text-white/30"></i>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Daftar User</h2>
        </div>

        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Bergabung</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Kursus</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Sertifikat</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Kuis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $index => $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $users->firstItem() + $index }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($user->foto)
                                            <img src="{{ Storage::url($user->foto) }}" class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                <i class="fas fa-user text-xs text-gray-600"></i>
                                            </div>
                                        @endif
                                        <span class="text-sm font-medium text-gray-800">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $user->role == 'admin' ? 'bg-red-100 text-red-700' : ($user->role == 'guru' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">{{ $user->enrollments_count ?? 0 }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">{{ $user->certificates_count ?? 0 }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full">{{ $user->quiz_attempts_count ?? 0 }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                <p class="text-gray-600 font-medium">Belum ada data user</p>
            </div>
        @endif
    </div>

</div>
@endsection
