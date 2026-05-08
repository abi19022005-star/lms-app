@extends('layouts.app')

@section('title', 'Detail Siswa - ' . $student->name)

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('guru.students.index') }}"
                   class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 font-poppins">
                        <i class="fas fa-user-graduate mr-3 text-blue-600"></i>Detail Siswa
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Informasi lengkap tentang siswa dan progres belajarnya</p>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('guru.students.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <a href="{{ route('guru.students.export', ['student_id' => $student->id]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Data
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Left Column - Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-8 text-center">
                    <div class="relative inline-block">
                        @if($student->foto)
                            <img src="{{ Storage::url($student->foto) }}" alt="{{ $student->name }}"
                                 class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-28 h-28 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white shadow-lg">
                                <i class="fas fa-user-graduate text-5xl text-white"></i>
                            </div>
                        @endif
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                            <div class="px-3 py-1 bg-emerald-500 rounded-full shadow-md">
                                <span class="text-xs font-semibold text-white">
                                    @php
                                        $avgProgress = $student->enrollments->avg('progress') ?? 0;
                                    @endphp
                                    {{ round($avgProgress) }}% Progress
                                </span>
                            </div>
                        </div>
                    </div>
                    <h2 class="text-white text-xl font-bold mt-4">{{ $student->name }}</h2>
                    <p class="text-blue-100 text-sm">{{ $student->email }}</p>
                    <div class="mt-3 flex justify-center gap-2">
                        <span class="inline-flex px-3 py-1 bg-white/20 rounded-full text-xs text-white">
                            <i class="fas fa-id-card mr-1"></i> ID: #{{ $student->id }}
                        </span>
                        <span class="inline-flex px-3 py-1 bg-white/20 rounded-full text-xs text-white">
                            <i class="fas fa-calendar-alt mr-1"></i> {{ $student->created_at->format('Y') }}
                        </span>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                        <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-envelope text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Email</p>
                            <p class="text-sm text-gray-700">{{ $student->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                        <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-check text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Bergabung Sejak</p>
                            <p class="text-sm text-gray-700">{{ $student->created_at->translatedFormat('d F Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $student->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Kursus Diambil</p>
                            <p class="text-sm text-gray-700 font-semibold">{{ $student->enrollments->count() }} Kursus</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid gap-4 grid-cols-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-trophy text-amber-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $student->certificates_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Sertifikat</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check-circle text-blue-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">
                        @php
                            $completedCount = $student->enrollments->where('status', 'completed')->count();
                        @endphp
                        {{ $completedCount }}
                    </p>
                    <p class="text-xs text-gray-500">Kursus Selesai</p>
                </div>
            </div>
        </div>

        <!-- Right Column - Enrollments & Progress -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Enrolled Courses -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-base font-semibold text-gray-800">
                        <i class="fas fa-book-open mr-2 text-blue-600"></i> Kursus yang Diambil
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Progres belajar siswa pada setiap kursus</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($student->enrollments as $enrollment)
                    <div class="p-5 hover:bg-gray-50 transition-colors" id="enrollment-{{ $enrollment->id }}">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $enrollment->course->judul }}</h4>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-clock mr-1"></i> {{ $enrollment->enrolled_at->translatedFormat('d F Y') }}
                                    </span>
                                    @if($enrollment->completed_at)
                                    <span class="text-xs text-emerald-600">
                                        <i class="fas fa-check-circle mr-1"></i> Selesai: {{ $enrollment->completed_at->translatedFormat('d F Y') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-lg {{ $enrollment->status == 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $enrollment->status == 'completed' ? 'Selesai' : 'Aktif' }}
                                </span>
                                <button type="button" onclick="toggleEditForm({{ $enrollment->id }})" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                                    <i class="fas fa-edit"></i> Kelola
                                </button>
                                <a href="{{ route('courses.show', $enrollment->course->slug ?? $enrollment->course->id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    Lihat Kursus
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-3">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Progress Belajar</span>
                                <span class="font-medium {{ $enrollment->progress >= 70 ? 'text-emerald-600' : ($enrollment->progress >= 40 ? 'text-amber-600' : 'text-red-600') }}">
                                    {{ $enrollment->progress }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="rounded-full h-2.5 transition-all duration-500
                                    {{ $enrollment->progress >= 70 ? 'bg-emerald-500' : ($enrollment->progress >= 40 ? 'bg-amber-500' : 'bg-red-500') }}"
                                    style="width: {{ $enrollment->progress }}%">
                                </div>
                            </div>
                        </div>

                        <!-- Lessons Progress Summary -->
                        @if($enrollment->course->lessons && $enrollment->course->lessons->count() > 0)
                        <div class="mt-3 flex items-center gap-4 text-xs text-gray-500">
                            <span><i class="far fa-file-alt mr-1"></i> Total Lesson: {{ $enrollment->course->lessons->count() }}</span>
                            @php
                                $completedLessons = $enrollment->course->lessons->filter(function($lesson) use ($student) {
                                    return $lesson->isCompletedBy($student->id);
                                })->count();
                            @endphp
                            <span class="text-emerald-600"><i class="fas fa-check-circle mr-1"></i> Selesai: {{ $completedLessons }}</span>
                            <span class="text-amber-600"><i class="fas fa-spinner mr-1"></i> Tersisa: {{ $enrollment->course->lessons->count() - $completedLessons }}</span>
                        </div>
                        @endif

                        <!-- Edit Form (Hidden by default) -->
                        <div class="mt-4 pt-4 border-t border-gray-200 hidden" id="edit-form-{{ $enrollment->id }}">
                            <form action="{{ route('guru.students.enrollments.update', ['studentId' => $student->id, 'enrollmentId' => $enrollment->id]) }}" 
                                  method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <!-- Progress Slider -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-2">
                                            <i class="fas fa-sliders-h mr-1"></i> Progress Belajar: <span class="font-semibold" id="progress-value-{{ $enrollment->id }}">{{ $enrollment->progress }}%</span>
                                        </label>
                                        <input type="range" name="progress" min="0" max="100" value="{{ $enrollment->progress }}"
                                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                               onchange="document.getElementById('progress-value-{{ $enrollment->id }}').textContent = this.value + '%'">
                                        <div class="mt-1 flex justify-between text-xs text-gray-500">
                                            <span>0%</span>
                                            <span>100%</span>
                                        </div>
                                    </div>

                                    <!-- Status Dropdown -->
                                    <div>
                                        <label for="status-{{ $enrollment->id }}" class="block text-xs font-medium text-gray-700 mb-2">
                                            <i class="fas fa-tasks mr-1"></i> Status Enrollment
                                        </label>
                                        <select name="status" id="status-{{ $enrollment->id }}" 
                                                class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="active" {{ $enrollment->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="completed" {{ $enrollment->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                            <option value="paused" {{ $enrollment->status === 'paused' ? 'selected' : '' }}>Ditangguhkan</option>
                                            <option value="cancelled" {{ $enrollment->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Info Box -->
                                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-800">
                                    <i class="fas fa-info-circle mr-1"></i> 
                                    Setelah status diubah menjadi "Selesai", siswa akan mendapatkan sertifikat jika lolos quiz.
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                    </button>
                                    <button type="button" onclick="toggleEditForm({{ $enrollment->id }})" 
                                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-300 transition-colors">
                                        <i class="fas fa-times mr-1"></i> Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <p class="mt-3 text-gray-500">Siswa belum mengambil kursus apapun</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Certificates Section -->
            @if(($student->certificates ?? collect())->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-base font-semibold text-gray-800">
                        <i class="fas fa-certificate mr-2 text-emerald-600"></i> Sertifikat yang Diperoleh
                    </h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($student->certificates as $certificate)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-certificate text-emerald-600 text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $certificate->course->judul }}</p>
                                    <p class="text-xs text-gray-500">
                                        Diterbitkan: {{ $certificate->issued_at->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('certificates.show', $certificate) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a href="{{ route('certificates.download', $certificate) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Activity Timeline -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-base font-semibold text-gray-800">
                        <i class="fas fa-history mr-2 text-purple-600"></i> Aktivitas Terbaru
                    </h3>
                </div>
                <div class="p-5">
                    <div class="space-y-4">
                        @php
                            $recentActivities = collect();
                            foreach($student->enrollments as $enrollment) {
                                if($enrollment->completed_at) {
                                    $recentActivities->push([
                                        'type' => 'completed',
                                        'title' => 'Menyelesaikan Kursus',
                                        'description' => 'Telah menyelesaikan kursus "' . $enrollment->course->judul . '"',
                                        'date' => $enrollment->completed_at
                                    ]);
                                }
                            }
                            $recentActivities = $recentActivities->sortByDesc('date')->take(5);
                        @endphp

                        @forelse($recentActivities as $activity)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center
                                    {{ $activity['type'] == 'completed' ? 'bg-emerald-100' : 'bg-blue-100' }}">
                                    <i class="fas {{ $activity['type'] == 'completed' ? 'fa-check-circle text-emerald-600' : 'fa-play-circle text-blue-600' }}"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $activity['title'] }}</p>
                                <p class="text-xs text-gray-500">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm">Belum ada aktivitas</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom scrollbar untuk timeline */
    .divide-y-gray-100 > :not([hidden]) ~ :not([hidden]) {
        border-color: #f3f4f6;
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
        background: #3b82f6;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    input[type="range"]::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #3b82f6;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
function toggleEditForm(enrollmentId) {
    const editForm = document.getElementById(`edit-form-${enrollmentId}`);
    editForm.classList.toggle('hidden');
}
</script>
@endpush
