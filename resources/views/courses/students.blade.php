@extends('layouts.app')

@section('title', $course->judul . ' - Daftar Siswa')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">{{ $course->judul }}</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar siswa yang terdaftar di kursus ini</p>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Siswa</h2>
            <a href="{{ route('courses.students.export', $course) }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                <i class="fas fa-download"></i>
                <span>Export CSV</span>
            </a>
        </div>

        <!-- Table -->
        @if($students->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Tanggal Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $enrollment)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $enrollment->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $enrollment->user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $enrollment->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-32">
                                        <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ $enrollment->progress }}%</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $enrollment->enrolled_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-center">
                                    <button onclick="toggleStudentLessons({{ $enrollment->user->id }})"
                                            class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-tasks mr-1"></i> Progress
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $students->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                <p class="text-gray-600 font-medium">Belum ada siswa yang terdaftar</p>
            </div>
        @endif
    </div>

    <!-- Student Progress Details (Modal/Expandable) -->
    <div id="studentProgressModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-100 p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Progress Pembelajaran</h2>
                    <p id="studentName" class="text-sm text-gray-500 mt-1"></p>
                </div>
                <button onclick="closeStudentProgress()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6 space-y-3" id="lessonsContainer">
                <!-- Lessons will be loaded here -->
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 border-t border-gray-100 p-6 text-right">
                <button onclick="closeStudentProgress()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    let selectedUserId = null;

    function toggleStudentLessons(userId) {
        selectedUserId = userId;
        
        // Find student data
        const students = @json($students->map(fn($e) => ['id' => $e->user->id, 'name' => $e->user->name, 'enrollment_id' => $e->id]));
        const student = students.find(s => s.id === userId);
        
        if (student) {
            document.getElementById('studentName').textContent = student.name;
            loadStudentLessons(userId);
            document.getElementById('studentProgressModal').classList.remove('hidden');
        }
    }

    function closeStudentProgress() {
        document.getElementById('studentProgressModal').classList.add('hidden');
        selectedUserId = null;
    }

    function loadStudentLessons(userId) {
        const courseId = '{{ $course->id }}';
        const container = document.getElementById('lessonsContainer');
        
        // Get lessons data from page
        const lessons = @json($course->lessons->map(fn($lesson) => [
            'id' => $lesson->id,
            'judul' => $lesson->judul,
            'tipe' => $lesson->tipe,
        ]));

        const lessonCompletions = @json(\App\Models\LessonCompletion::whereIn('lesson_id', $course->lessons->pluck('id'))->get(['user_id', 'lesson_id'])->groupBy('user_id'));

        const completedLessons = (lessonCompletions[userId] || []).map(lc => lc.lesson_id);

        let html = '';
        lessons.forEach((lesson, index) => {
            const isCompleted = completedLessons.includes(lesson.id);
            html += `
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            ${isCompleted ? 
                                '<div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center"><i class="fas fa-check text-emerald-600"></i></div>'
                                : '<div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 font-medium">' + (index + 1) + '</div>'
                            }
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">${lesson.judul}</p>
                            <p class="text-xs text-gray-500"><i class="fas fa-${lesson.tipe === 'video' ? 'video' : (lesson.tipe === 'pdf' ? 'file-pdf' : 'file-alt')}"></i> ${lesson.tipe.charAt(0).toUpperCase() + lesson.tipe.slice(1)}</p>
                        </div>
                    </div>
                    <div>
                        ${isCompleted ? 
                            '<span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">Selesai</span>'
                            : '<button onclick="markLessonComplete(' + lesson.id + ', ' + userId + ')" class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full hover:bg-blue-200" id="btn-' + lesson.id + '-' + userId + '">Tandai Selesai</button>'
                        }
                    </div>
                </div>
            `;
        });

        container.innerHTML = html || '<p class="text-gray-500">Tidak ada lessons</p>';
    }

    function markLessonComplete(lessonId, userId) {
        const btn = document.getElementById('btn-' + lessonId + '-' + userId);
        const courseId = '{{ $course->id }}';

        if (!confirm('Tandai pembelajaran ini sebagai selesai untuk siswa?')) {
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner animate-spin mr-1"></i> Loading...';

        fetch(`/courses/${courseId}/lessons/${lessonId}/mark-complete/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.text())
        .then(data => {
            if (response.ok) {
                btn.classList.remove('bg-blue-100', 'text-blue-700', 'hover:bg-blue-200');
                btn.classList.add('bg-emerald-100', 'text-emerald-700');
                btn.innerHTML = '<i class="fas fa-check mr-1"></i> Selesai';
                btn.disabled = true;
                
                // Reload lessons to refresh
                setTimeout(() => loadStudentLessons(userId), 1000);
            } else {
                alert('Gagal menandai pembelajaran. Coba lagi.');
                btn.disabled = false;
                btn.innerHTML = 'Tandai Selesai';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Coba lagi.');
            btn.disabled = false;
            btn.innerHTML = 'Tandai Selesai';
        });
    }

    // Close modal when clicking outside
    document.getElementById('studentProgressModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeStudentProgress();
        }
    });
</script>
@endpush

