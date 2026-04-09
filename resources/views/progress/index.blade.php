@extends('layouts.app')

@section('title', 'Progress Belajar')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            📊 Progress Belajar
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Pantau perkembangan belajar kamu di setiap kursus
        </p>
    </div>

    <!-- List -->
    <div class="space-y-4">

        @forelse($progressData as $item)

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">

                <div class="flex justify-between items-center mb-3">

                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            {{ $item['course']->judul }}
                        </h3>

                        <p class="text-xs text-gray-400">
                            {{ $item['completed'] }} / {{ $item['total'] }} lesson selesai
                        </p>
                    </div>

                    <span class="text-sm font-bold text-blue-600">
                        {{ $item['progress'] }}%
                    </span>

                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all"
                         style="width: {{ $item['progress'] }}%"></div>
                </div>

                <!-- Action -->
                <div class="mt-3 text-right">
                    <a href="{{ route('courses.show', $item['course']) }}"
                       class="text-xs text-blue-600 hover:underline">
                        Lanjut Belajar →
                    </a>
                </div>

            </div>

        @empty

            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100">
                <p class="text-gray-500">Belum ada progress belajar</p>
                <a href="{{ route('courses.index') }}"
                   class="text-blue-600 text-sm hover:underline">
                    Cari kursus
                </a>
            </div>

        @endforelse

    </div>

</div>
@endsection
