@extends('layouts.app')

@section('title', $lesson->judul . ' - Preview')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back -->
    <a href="{{ route('courses.show', $lesson->course) }}"
       class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm">
        <i class="fas fa-arrow-left"></i> Back
    </a>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Header -->
        <div class="bg-blue-600 text-white px-5 py-4">
            <h4 class="font-semibold">
                {{ $lesson->judul }} (Preview)
            </h4>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-4">

            @if($lesson->tipe === 'video')
                <div class="aspect-video">
                    <iframe src="{{ $lesson->url_video }}"
                            class="w-full h-full rounded-xl"
                            allowfullscreen></iframe>
                </div>

            @elseif($lesson->tipe === 'teks')
                <div class="prose max-w-none text-sm">
                    {!! $lesson->konten_teks !!}
                </div>

            @elseif($lesson->tipe === 'pdf')
                <div class="bg-blue-50 text-blue-700 text-sm p-4 rounded-xl">
                    PDF hanya bisa diakses oleh siswa yang terdaftar
                </div>
            @endif

        </div>

    </div>

    <!-- Action -->
    @if(auth()->check())
    <div>
        <a href="{{ route('courses.show',$lesson->course) }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm">
            View Full Course
        </a>
    </div>
    @else
    <div class="bg-amber-50 text-amber-700 p-4 rounded-xl text-sm">
        <a href="{{ route('login') }}" class="underline">Login</a>
        atau
        <a href="{{ route('register') }}" class="underline">Register</a>
        untuk akses penuh
    </div>
    @endif

</div>
@endsection
