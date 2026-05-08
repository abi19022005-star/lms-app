@extends('layouts.app')

@section('title', 'Siswa Saya')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                <i class="fas fa-users mr-3 text-blue-600"></i>Siswa Saya
            </h1>
            <p class="text-sm text-gray-500 mt-1">Lihat semua siswa yang mengambil kursus yang Anda ajar</p>
        </div>
        <a href="{{ route('guru.students.export', request()->query()) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export CSV
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari siswa</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Nama atau email...">
            </div>
            <div class="min-w-[180px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Pilih Kursus</label>
                <select name="course_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kursus Saya</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->judul }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
            <div>
                <a href="{{ route('guru.students.index') }}" class="inline-flex px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-all">
                    <i class="fas fa-sync mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Students Cards / Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h6 class="text-sm font-semibold text-gray-700">
                    <i class="fas fa-list mr-2"></i> Daftar Siswa
                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $students->total() }} Total</span>
                </h6>
            </div>
            <div id="bulk-action-indicator" class="hidden text-xs font-medium text-blue-600">
                <i class="fas fa-check-circle mr-1"></i> <span id="selected-count">0</span> siswa dipilih
            </div>
        </div>

        <!-- Bulk Action Panel -->
        <div id="bulk-action-panel" class="hidden bg-blue-50 border-b border-blue-200 px-5 py-4">
            <form id="bulk-form" action="{{ route('guru.students.bulk-update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-3 items-end">
                    <!-- Action Type -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">
                            <i class="fas fa-cogs mr-1"></i> Tindakan Massal
                        </label>
                        <select name="action" id="bulk-action" class="w-full px-3 py-2 text-xs border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="updateBulkActionValue()">
                            <option value="set_progress">Atur Progress</option>
                            <option value="set_status">Atur Status</option>
                        </select>
                    </div>

                    <!-- Progress Value -->
                    <div id="progress-control">
                        <label class="block text-xs font-medium text-gray-700 mb-2">
                            <i class="fas fa-percentage mr-1"></i> Progress: <span id="progress-display">0</span>%
                        </label>
                        <input type="range" name="value" min="0" max="100" value="0" 
                               class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer"
                               onchange="document.getElementById('progress-display').textContent = this.value">
                    </div>

                    <!-- Status Value -->
                    <div id="status-control" class="hidden">
                        <label class="block text-xs font-medium text-gray-700 mb-2">
                            <i class="fas fa-tasks mr-1"></i> Status
                        </label>
                        <select name="value" class="w-full px-3 py-2 text-xs border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Aktif</option>
                            <option value="completed">Selesai</option>
                            <option value="paused">Ditangguhkan</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-check mr-1"></i> Terapkan
                        </button>
                        <button type="button" onclick="clearSelection()" class="flex-1 px-3 py-2 bg-gray-300 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-400 transition-colors">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                    </div>
                </div>

                <!-- Hidden input untuk enrollment IDs -->
                <input type="hidden" id="enrollment-ids" name="enrollment_ids">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="students-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                            <input type="checkbox" id="select-all" class="rounded" onchange="toggleSelectAll(this)">
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kursus</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Progres</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Bergabung</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $index => $student)
                        @foreach($student->enrollments as $enrollment)
                            @if($courses->pluck('id')->contains($enrollment->course_id))
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-5 py-3 text-center">
                                    <input type="checkbox" class="enrollment-checkbox rounded" value="{{ $enrollment->id }}" onchange="updateBulkSelection()">
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-500">
                                    {{ $students->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($student->foto)
                                            <img src="{{ Storage::url($student->foto) }}"
                                                 class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium shadow-sm">
                                                {{ strtoupper(substr($student->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $student->name }}</p>
                                            <p class="text-xs text-gray-400">ID: #{{ $student->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-600">
                                    {{ $student->email }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700">
                                        {{ $enrollment->course->judul }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[100px]">
                                            <div class="rounded-full h-2 transition-all duration-500
                                                {{ $enrollment->progress >= 70 ? 'bg-emerald-500' : ($enrollment->progress >= 40 ? 'bg-amber-500' : 'bg-red-500') }}"
                                                style="width: {{ $enrollment->progress }}%">
                                            </div>
                                        </div>
                                        <span class="text-xs font-medium {{ $enrollment->progress >= 70 ? 'text-emerald-600' : ($enrollment->progress >= 40 ? 'text-amber-600' : 'text-red-600') }}">
                                            {{ $enrollment->progress }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @if($enrollment->status == 'completed')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-lg bg-emerald-100 text-emerald-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-lg bg-amber-100 text-amber-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="text-sm text-gray-600">{{ $enrollment->enrolled_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $enrollment->enrolled_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <a href="{{ route('guru.students.show', $student->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                                       title="Detail Siswa">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center">
                                <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <h5 class="mt-4 text-lg font-medium text-gray-700">Belum ada siswa</h5>
                                <p class="text-gray-400 mt-1">Belum ada siswa yang mengambil kursus Anda</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-3">
            <div class="text-xs text-gray-500">
                Menampilkan {{ $students->firstItem() }} sampai {{ $students->lastItem() }} dari {{ $students->total() }} data
            </div>
            <div>
                {{ $students->withQueryString()->links() }}
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
    /* Custom pagination styling */
    .pagination {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
    }
    .pagination .page-item .page-link {
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        color: #4b5563;
        background-color: white;
        border: 1px solid #e5e7eb;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .pagination .page-item.active .page-link {
        background-color: #2563eb;
        border-color: #2563eb;
        color: white;
    }
    .pagination .page-item .page-link:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }

    /* Range slider styling */
    input[type="range"] {
        -webkit-appearance: none;
        width: 100%;
        height: 6px;
        border-radius: 5px;
        background: #e5e7eb;
        outline: none;
        -webkit-slider-thumb-appearance: none;
    }
    
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #2563eb;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    input[type="range"]::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #2563eb;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
function toggleSelectAll(checkbox) {
    const enrollmentCheckboxes = document.querySelectorAll('.enrollment-checkbox');
    enrollmentCheckboxes.forEach(el => {
        el.checked = checkbox.checked;
    });
    updateBulkSelection();
}

function updateBulkSelection() {
    const selectedCheckboxes = document.querySelectorAll('.enrollment-checkbox:checked');
    const bulkPanel = document.getElementById('bulk-action-panel');
    const bulkIndicator = document.getElementById('bulk-action-indicator');
    const selectedCount = document.getElementById('selected-count');
    const enrollmentIdsInput = document.getElementById('enrollment-ids');
    
    const enrollmentIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (enrollmentIds.length > 0) {
        bulkPanel.classList.remove('hidden');
        bulkIndicator.classList.remove('hidden');
        selectedCount.textContent = enrollmentIds.length;
        enrollmentIdsInput.value = JSON.stringify(enrollmentIds);
        
        // Update select all checkbox
        const allCheckboxes = document.querySelectorAll('.enrollment-checkbox');
        document.getElementById('select-all').checked = allCheckboxes.length === selectedCheckboxes.length;
    } else {
        bulkPanel.classList.add('hidden');
        bulkIndicator.classList.add('hidden');
        enrollmentIdsInput.value = '';
        document.getElementById('select-all').checked = false;
    }
}

function updateBulkActionValue() {
    const action = document.getElementById('bulk-action').value;
    const progressControl = document.getElementById('progress-control');
    const statusControl = document.getElementById('status-control');
    
    if (action === 'set_progress') {
        progressControl.classList.remove('hidden');
        statusControl.classList.add('hidden');
    } else {
        progressControl.classList.add('hidden');
        statusControl.classList.remove('hidden');
    }
}

function clearSelection() {
    document.querySelectorAll('.enrollment-checkbox').forEach(cb => cb.checked = false);
    updateBulkSelection();
}

// Form submission
document.getElementById('bulk-form').addEventListener('submit', function(e) {
    const enrollmentIds = document.getElementById('enrollment-ids').value;
    if (!enrollmentIds || JSON.parse(enrollmentIds).length === 0) {
        e.preventDefault();
        alert('Pilih minimal satu enrollment');
        return false;
    }
});
</script>
@endpush
@endsection
