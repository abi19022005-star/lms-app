@extends('layouts.app')

@section('title', 'Verifikasi Sertifikat')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    @if($valid)
        <!-- Success State -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold font-poppins">✓ Sertifikat Valid</h1>
                    <p class="text-emerald-100 mt-1">{{ $message }}</p>
                </div>
                <i class="fas fa-check-circle text-5xl text-white/30"></i>
            </div>
        </div>

        <!-- Certificate Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="text-center border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $certificate->course->judul }}</h2>
                <p class="text-gray-500 mt-1">Sertifikat Penyelesaian Kursus</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Penerima</p>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $certificate->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Email</p>
                        <p class="text-sm text-gray-700 mt-1">{{ $certificate->user->email }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Tanggal Terbit</p>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $certificate->issued_at->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Kode Unik</p>
                        <p class="text-sm font-mono text-gray-700 mt-1 bg-gray-50 p-2 rounded-lg">{{ $certificate->kode_unik }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Error State -->
        <div class="bg-gradient-to-r from-red-600 to-red-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold font-poppins">✗ Sertifikat Tidak Valid</h1>
                    <p class="text-red-100 mt-1">{{ $message }}</p>
                </div>
                <i class="fas fa-times-circle text-5xl text-white/30"></i>
            </div>
        </div>

        <!-- Help Message -->
        <div class="bg-blue-50 text-blue-700 text-sm p-4 rounded-xl border border-blue-200">
            <p class="flex items-center gap-2 font-semibold mb-2">
                <i class="fas fa-info-circle"></i>
                <span>Troubleshooting</span>
            </p>
            <ul class="list-disc list-inside space-y-1 text-xs">
                <li>Pastikan Anda memasukkan kode sertifikat yang benar</li>
                <li>Periksa ejaan dan karakter khusus</li>
                <li>Hubungi administrator jika masalah berlanjut</li>
            </ul>
        </div>
    @endif

</div>
@endsection
