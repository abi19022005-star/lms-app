@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-chart-line mr-3 text-blue-600"></i>Admin Dashboard
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Selamat datang, {{ auth()->user()->name }} 👋
        </p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- Users -->
        <div class="bg-blue-600 text-white rounded-2xl p-5 shadow-sm">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm opacity-80">Total Users</p>
                    <h2 class="text-2xl font-bold">{{ $totalUsers ?? 0 }}</h2>
                    <p class="text-xs mt-1 opacity-70">
                        Guru: {{ $totalGurus ?? 0 }} | Siswa: {{ $totalSiswas ?? 0 }}
                    </p>
                </div>
                <i class="fas fa-users text-3xl opacity-40"></i>
            </div>
        </div>

        <!-- Courses -->
        <div class="bg-emerald-600 text-white rounded-2xl p-5 shadow-sm">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm opacity-80">Total Courses</p>
                    <h2 class="text-2xl font-bold">{{ $totalCourses ?? 0 }}</h2>
                    <p class="text-xs mt-1 opacity-70">
                        Published: {{ $totalPublishedCourses ?? 0 }}
                    </p>
                </div>
                <i class="fas fa-book text-3xl opacity-40"></i>
            </div>
        </div>

        <!-- Enrollments -->
        <div class="bg-cyan-600 text-white rounded-2xl p-5 shadow-sm">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm opacity-80">Enrollments</p>
                    <h2 class="text-2xl font-bold">{{ $totalEnrollments ?? 0 }}</h2>
                </div>
                <i class="fas fa-graduation-cap text-3xl opacity-40"></i>
            </div>
        </div>

        <!-- Certificates -->
        <div class="bg-amber-500 text-white rounded-2xl p-5 shadow-sm">
            <div class="flex justify-between">
                <div>
                    <p class="text-sm opacity-80">Certificates</p>
                    <h2 class="text-2xl font-bold">{{ $totalCertificates ?? 0 }}</h2>
                </div>
                <i class="fas fa-certificate text-3xl opacity-40"></i>
            </div>
        </div>

    </div>

    <!-- Chart + Revenue -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <!-- Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h6 class="text-sm font-semibold text-gray-700 mb-4">
                <i class="fas fa-chart-bar mr-2"></i>Statistik Bulanan
            </h6>
            <canvas id="monthlyChart" height="200"></canvas>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h6 class="text-sm font-semibold text-gray-700 mb-4">
                <i class="fas fa-money-bill-wave mr-2"></i>Revenue
            </h6>

            <div class="text-center mb-4">
                <h2 class="text-2xl font-bold text-emerald-600">
                    Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                </h2>
                <p class="text-sm text-gray-400">Total Pendapatan</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-lg font-semibold text-gray-700">
                        {{ number_format(($totalEnrollments ?? 0) > 0 ? (($totalCertificates ?? 0) / ($totalEnrollments ?? 1)) * 100 : 0, 1) }}%
                    </p>
                    <p class="text-xs text-gray-400">Completion</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-lg font-semibold text-gray-700">
                        {{ number_format(($totalPublishedCourses ?? 0) > 0 ? (($totalPublishedCourses ?? 0) / ($totalCourses ?? 1)) * 100 : 0, 1) }}%
                    </p>
                    <p class="text-xs text-gray-400">Published</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Table -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <!-- Recent Courses -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b">
                <h6 class="text-sm font-semibold text-gray-700">
                    <i class="fas fa-clock mr-2"></i>Recent Courses
                </h6>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Guru</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($recentCourses ?? [] as $course)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ Str::limit($course->judul, 25) }}</td>
                        <td class="px-4 py-3">{{ $course->guru->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-lg
                                {{ $course->status == 'published'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-gray-400">
                            Belum ada kursus
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b">
                <h6 class="text-sm font-semibold text-gray-700">
                    <i class="fas fa-users mr-2"></i>Recent Users
                </h6>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($recentUsers ?? [] as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-lg
                                {{ $user->role == 'admin'
                                    ? 'bg-red-100 text-red-700'
                                    : ($user->role == 'guru'
                                        ? 'bg-amber-100 text-amber-700'
                                        : 'bg-blue-100 text-blue-700') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $user->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-gray-400">
                            Belum ada user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('monthlyChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json(array_column($monthlyStats ?? [], 'month')),
        datasets: [
            {
                label: 'Users',
                data: @json(array_column($monthlyStats ?? [], 'users')),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Courses',
                data: @json(array_column($monthlyStats ?? [], 'courses')),
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>
@endpush

@endsection
