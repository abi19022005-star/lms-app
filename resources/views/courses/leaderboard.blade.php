@extends('layouts.app')

@section('title', $course->judul . ' - Papan Peringkat')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">{{ $course->judul }}</h1>
        <p class="text-sm text-gray-500 mt-1">Papan peringkat siswa yang telah menyelesaikan kursus</p>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-blue-50">
            <h2 class="text-lg font-semibold text-gray-800">🏆 Siswa Terbaik</h2>
        </div>

        <!-- Table -->
        @if($leaderboard->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Peringkat</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nama Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Tanggal Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaderboard as $index => $enrollment)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    @if($index < 3)
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full font-bold text-white {{ $index === 0 ? 'bg-yellow-400' : ($index === 1 ? 'bg-gray-400' : 'bg-orange-400') }}">
                                            @if($index === 0)
                                                🥇
                                            @elseif($index === 1)
                                                🥈
                                            @else
                                                🥉
                                            @endif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 font-bold text-gray-700">
                                            {{ $index + 1 }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $enrollment->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $enrollment->completed_at?->format('d M Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $leaderboard->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                <p class="text-gray-600 font-medium">Belum ada siswa yang menyelesaikan kursus</p>
            </div>
        @endif
    </div>

</div>
@endsection
