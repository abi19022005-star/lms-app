@extends('layouts.app')

@section('title', 'Sertifikat Saya')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-certificate text-amber-500 mr-3"></i>Sertifikat Saya
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Kelola dan lihat semua sertifikat yang telah Anda dapatkan
        </p>
    </div>

    @if($certificates->isEmpty())
    <!-- EMPTY -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 text-center py-12">
        <i class="fas fa-certificate text-5xl text-gray-300 mb-4"></i>
        <h4 class="text-gray-600 font-semibold mb-2">Belum Ada Sertifikat</h4>
        <p class="text-gray-400 mb-4">Selesaikan kursus untuk mendapatkan sertifikat</p>

        <a href="{{ route('courses.index') }}"
           class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
            Jelajahi Kursus
        </a>
    </div>

    @else

    <!-- LIST -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        @foreach($certificates as $certificate)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

            <!-- HEADER -->
            <div class="p-5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-medal text-yellow-300 text-2xl"></i>
                        <div>
                            <p class="font-semibold">Sertifikat Selesai</p>
                            <p class="text-xs opacity-80">ID: {{ $certificate->kode_unik }}</p>
                        </div>
                    </div>

                    <span class="bg-yellow-300 text-black text-xs px-2 py-1 rounded-lg">
                        ✔ Terverifikasi
                    </span>
                </div>
            </div>

            <!-- BODY -->
            <div class="p-5 space-y-4">

                <h5 class="font-semibold text-blue-600">
                    <i class="fas fa-book mr-1"></i>{{ $certificate->course->judul }}
                </h5>

                <!-- INFO -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-400 text-xs">Peserta</p>
                        <p class="font-medium">{{ $certificate->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">Tanggal</p>
                        <p class="font-medium">{{ $certificate->issued_at->format('d F Y') }}</p>
                    </div>
                </div>

                <!-- CODE -->
                <div class="bg-gray-50 p-3 rounded-xl">
                    <p class="text-xs text-gray-400">Kode Sertifikat</p>
                    <p class="font-mono text-sm font-semibold text-gray-800">
                        {{ $certificate->kode_unik }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        ✔ Bisa diverifikasi online
                    </p>
                </div>

                <!-- DETAIL -->
                <div class="grid grid-cols-3 text-center text-xs">
                    <div>
                        <p class="text-gray-400">Instruktur</p>
                        <p class="font-semibold">{{ $certificate->course->guru->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Kategori</p>
                        <p class="font-semibold">{{ $certificate->course->kategori->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Status</p>
                        <span class="bg-emerald-100 text-emerald-700 px-3 py-0.45 rounded-lg text-xs">
                            Aktif
                        </span>
                    </div>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="p-4 border-t flex gap-2">

                <a href="{{ route('certificates.preview', $certificate) }}"
                   target="_blank"
                   class="flex-1 text-center text-sm px-3 py-2 border border-blue-500 text-blue-600 rounded-xl hover:bg-blue-50">
                    Lihat
                </a>

                <a href="{{ route('certificates.download', $certificate) }}"
                   class="flex-1 text-center text-sm px-3 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                    Download
                </a>

                <button onclick="copyToClipboard('{{ $certificate->kode_unik }}')"
                        class="px-3 py-2 border rounded-xl hover:bg-gray-100">
                    <i class="fas fa-copy"></i>
                </button>

            </div>

        </div>
        @endforeach

    </div>

    <!-- PAGINATION -->
    @if($certificates->hasPages())
    <div class="pt-6">
        {{ $certificates->links() }}
    </div>
    @endif

    @endif

    <!-- VERIFICATION -->
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
        <h6 class="font-semibold text-blue-700 mb-2">
            <i class="fas fa-info-circle mr-2"></i>Verifikasi Sertifikat
        </h6>
        <p class="text-sm text-blue-600">
            Sertifikat dapat diverifikasi menggunakan kode unik.
        </p>
    </div>

</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Kode berhasil disalin!');
    }).catch(() => {
        alert('Gagal menyalin');
    });
}
</script>
@endpush

@endsection
