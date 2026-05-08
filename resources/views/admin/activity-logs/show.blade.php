@extends('layouts.app')

@section('title', 'Detail Activity Log ' . ($log->user_name ?? $log->user_id))

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins" >
                <i class="fas fa-info-circle mr-3 text-blue-600" ></i>Detail Activity Log {{ $log->user_name ?? $log->user_id }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">Informasi lengkap aktivitas sistem</p>
        </div>
        <a href="{{ route('admin.activity-logs.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Left Column - Basic Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-4">
                <h6 class="text-sm font-semibold text-white"><i class="fas fa-info-circle mr-2"></i> Informasi Dasar</h6>
            </div>
            <div class="p-5 space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">ID</span>
                    <span class="text-sm font-medium text-gray-800"> {{  $log->user_id }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">User</span>
                    <span class="text-sm font-medium text-gray-800">
                        @if($log->user_id)
                            <a href="{{ route('admin.users.edit', $log->user_id) }}" class="text-blue-600 hover:text-blue-700">
                                User {{ $log->user_name ?? $log->user_id }}
                            </a>
                        @else
                            <span class="text-gray-400">System</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Action</span>
                    <span>
                        @php
                            $actionClass = str_contains($log->action, 'create') ? 'bg-emerald-100 text-emerald-700' :
                                (str_contains($log->action, 'update') ? 'bg-amber-100 text-amber-700' :
                                (str_contains($log->action, 'delete') ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'));
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg {{ $actionClass }}">
                            {{ $log->action }}
                        </span>
                    </span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Model Type</span>
                    <span class="text-sm text-gray-600">{{ $log->model_type ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Model ID</span>
                    <span class="text-sm text-gray-600">{{ $log->model_id ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">IP Address</span>
                    <span class="text-sm font-mono text-gray-600">{{ $log->ip_address ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Created At</span>
                    <div class="text-right">
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($log->created_at)->format('d F Y H:i:s') }}</span>
                        <br>
                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Description & Data -->
        <div class="space-y-6">
            <!-- Description Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-600 to-gray-500 px-5 py-4">
                    <h6 class="text-sm font-semibold text-white"><i class="fas fa-align-left mr-2"></i> Deskripsi</h6>
                </div>
                <div class="p-5">
                    <p class="text-gray-600 leading-relaxed">{{ $log->description ?? '-' }}</p>
                    @if($log->user_agent)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-400 mb-1">User Agent</p>
                            <p class="text-xs text-gray-500 break-all">{{ $log->user_agent }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Old Data Card -->
            @if($log->old_data)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-600 to-amber-500 px-5 py-4">
                    <h6 class="text-sm font-semibold text-white"><i class="fas fa-history mr-2"></i> Old Data</h6>
                </div>
                <div class="p-5">
                    <pre class="bg-gray-50 p-3 rounded-xl text-xs font-mono text-gray-600 overflow-auto max-h-64"><code>{{ json_encode(json_decode($log->old_data), JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
            @endif

            <!-- New Data Card -->
            @if($log->new_data)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 px-5 py-4">
                    <h6 class="text-sm font-semibold text-white"><i class="fas fa-database mr-2"></i> New Data</h6>
                </div>
                <div class="p-5">
                    <pre class="bg-gray-50 p-3 rounded-xl text-xs font-mono text-gray-600 overflow-auto max-h-64"><code>{{ json_encode(json_decode($log->new_data), JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
