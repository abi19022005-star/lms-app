@extends('layouts.app')

@section('title', 'Certificate')

@section('content')
<div class="flex justify-center mt-6">

    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center py-6">
            <h2 class="text-2xl font-bold tracking-wide">CERTIFICATE</h2>
            <p class="text-sm opacity-80">OF COMPLETION</p>
        </div>

        <!-- BODY -->
        <div class="p-8 text-center space-y-5">

            <!-- Title -->
            <h3 class="text-xl font-semibold text-gray-800">
                {{ $certificate->course->judul }}
            </h3>

            <!-- Subtitle -->
            <p class="text-gray-500 text-sm">
                This certificate is proudly presented to
            </p>

            <!-- Name -->
            <h1 class="text-2xl font-bold text-indigo-600">
                {{ $certificate->user->name }}
            </h1>

            <!-- Description -->
            <p class="text-gray-600 text-sm">
                For successfully completing the course
            </p>

            <!-- Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 text-sm">

                <div class="bg-gray-50 p-3 rounded-xl">
                    <p class="text-gray-400">Issued Date</p>
                    <p class="font-semibold text-gray-700">
                        {{ $certificate->issued_at->format('d M Y') }}
                    </p>
                </div>

                <div class="bg-gray-50 p-3 rounded-xl">
                    <p class="text-gray-400">Certificate Code</p>
                    <p class="font-mono text-gray-800 font-semibold">
                        {{ $certificate->kode_unik }}
                    </p>
                </div>

            </div>

            <!-- Divider -->
            <div class="border-t pt-5 mt-6">

                <div class="flex flex-col md:flex-row justify-between items-center gap-4">

                    <!-- Instructor -->
                    <div class="text-center">
                        <div class="w-40 border-t mx-auto mb-2"></div>
                        <p class="text-sm font-semibold text-gray-700">
                            {{ $certificate->course->guru->name ?? 'Instructor' }}
                        </p>
                        <p class="text-xs text-gray-400">Instructor</p>
                    </div>

                    <!-- Platform -->
                    <div class="text-center">
                        <div class="w-40 border-t mx-auto mb-2"></div>
                        <p class="text-sm font-semibold text-gray-700">
                            LMS Platform
                        </p>
                        <p class="text-xs text-gray-400">Authorized By</p>
                    </div>

                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="bg-gray-50 p-4 flex justify-center gap-3">

            <a href="{{ route('certificates.download', $certificate) }}"
               class="px-5 py-2 bg-blue-600 text-white text-sm rounded-xl hover:bg-blue-700">
                <i class="fas fa-download mr-2"></i>Download PDF
            </a>

        </div>

    </div>

</div>
@endsection
