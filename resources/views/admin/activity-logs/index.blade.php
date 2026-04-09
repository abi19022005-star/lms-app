@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-history mr-3 text-blue-600"></i>Activity Logs
            </h1>
            <p class="text-sm text-gray-500 mt-1">Riwayat aktivitas sistem</p>
        </div>
        <button type="button" onclick="clearLogs()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Hapus Semua Log
        </button>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">User ID</label>
                <input type="text" name="user_id" value="{{ request('user_id') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="User ID">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Action</label>
                <input type="text" name="action" value="{{ request('action') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Action">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
            <div>
                <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-all">
                    <i class="fas fa-sync mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Action</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Model</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">IP Address</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Time</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $log->id }}</td>
                        <td class="px-4 py-3">
                            @if($log->user_id)
                                <a href="{{ route('admin.users.edit', $log->user_name) }}" class="text-blue-600 hover:text-blue-700 text-sm">
                                    {{ $log->user_name }}
                                </a>
                            @else
                                <span class="text-sm text-gray-400">System</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $actionClass = str_contains($log->action, 'create') ? 'bg-emerald-100 text-emerald-700' :
                                    (str_contains($log->action, 'update') ? 'bg-amber-100 text-amber-700' :
                                    (str_contains($log->action, 'delete') ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'));
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg {{ $actionClass }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            @if($log->model_type)
                                {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">
                            {{ Str::limit($log->description, 50) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $log->ip_address ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</div>
                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.activity-logs.show', $log->id) }}"
                               class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <h5 class="mt-4 text-lg font-medium text-gray-700">Tidak ada data log</h5>
                            <p class="text-gray-400">Belum ada aktivitas yang tercatat</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" id="clearLogsModal">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 transform transition-all">
        <div class="bg-red-600 rounded-t-2xl px-6 py-4">
            <h5 class="text-lg font-semibold text-white">Konfirmasi Hapus Semua Log</h5>
        </div>
        <div class="p-6">
            <p>Apakah Anda yakin ingin menghapus semua log aktivitas?</p>
            <p class="text-red-600 text-sm mt-2">Tindakan ini tidak dapat dibatalkan!</p>
            <form id="clearLogsForm" action="{{ route('admin.activity-logs.clear') }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div>
        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
            <button type="button" onclick="closeClearModal()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
            <button type="submit" form="clearLogsForm" class="px-4 py-2 text-sm text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i> Hapus Semua
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function clearLogs() {
        document.getElementById('clearLogsModal').classList.remove('hidden');
        document.getElementById('clearLogsModal').classList.add('flex');
    }
    function closeClearModal() {
        document.getElementById('clearLogsModal').classList.add('hidden');
        document.getElementById('clearLogsModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
