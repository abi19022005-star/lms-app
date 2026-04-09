@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-chart-bar mr-3 text-blue-600"></i>Laporan
        </h1>
        <p class="text-sm text-gray-500 mt-1">Statistik dan analisis data sistem</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-blue-100">Total Users</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalUsers }}</p>
                    <p class="text-xs text-blue-100 mt-2">Guru: {{ $totalGurus }} | Siswa: {{ $totalSiswas }}</p>
                </div>
                <i class="fas fa-users text-3xl text-white/30"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-emerald-100">Total Courses</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalCourses }}</p>
                    <p class="text-xs text-emerald-100 mt-2">Published: {{ $totalPublishedCourses }}</p>
                </div>
                <i class="fas fa-book text-3xl text-white/30"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-600 to-cyan-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-cyan-100">Enrollments</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalEnrollments }}</p>
                    <p class="text-xs text-cyan-100 mt-2">Completed: {{ $completedEnrollments }}</p>
                </div>
                <i class="fas fa-graduation-cap text-3xl text-white/30"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-600 to-amber-500 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-amber-100">Revenue</p>
                    <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="text-xs text-amber-100 mt-2">Certificates: {{ $totalCertificates }}</p>
                </div>
                <i class="fas fa-money-bill-wave text-3xl text-white/30"></i>
            </div>
        </div>
    </div>

    <!-- Chart & Top Courses -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h6 class="text-sm font-semibold text-gray-700 mb-4">
                <i class="fas fa-chart-line mr-2 text-blue-600"></i> Monthly Trends (6 Months)
            </h6>
            <canvas id="trendsChart" height="250"></canvas>
        </div>

        <!-- Top Courses -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h6 class="text-sm font-semibold text-gray-700">
                    <i class="fas fa-trophy mr-2 text-amber-500"></i> Top Courses
                </h6>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($topCourses as $course)
                <div class="px-5 py-3 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ Str::limit($course->judul, 35) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $course->enrollments_count }} enrollments</p>
                        </div>
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-600 text-xs font-bold rounded-full">
                            {{ $loop->iteration }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Enrollments -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h6 class="text-sm font-semibold text-gray-700">
                <i class="fas fa-clock mr-2 text-gray-500"></i> Recent Enrollments
            </h6>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Student</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Course</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Enrolled At</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Progress</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentEnrollments as $enrollment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $enrollment->user->name }}</td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $enrollment->course->judul }}</td>
                        <td class="px-5 py-3 text-sm text-gray-500">{{ $enrollment->enrolled_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 w-24">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600">{{ $enrollment->progress }}%</span>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg {{ $enrollment->status == 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('trendsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_column($monthlyData, 'month')),
            datasets: [
                {
                    label: 'Users',
                    data: @json(array_column($monthlyData, 'users')),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Courses',
                    data: @json(array_column($monthlyData, 'courses')),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Enrollments',
                    data: @json(array_column($monthlyData, 'enrollments')),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
@endsection
