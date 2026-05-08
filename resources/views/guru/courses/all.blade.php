@extends('layouts.app')

@section('title', 'Semua Kursus')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold">
                <i class="fas fa-globe me-3 text-primary"></i>Semua Kursus
            </h1>
            <p class="text-muted">Jelajahi semua kursus yang tersedia</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari kursus..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="price" class="form-select">
                        <option value="">Semua Harga</option>
                        <option value="free" {{ request('price') == 'free' ? 'selected' : '' }}>Gratis</option>
                        <option value="paid" {{ request('price') == 'paid' ? 'selected' : '' }}>Berbayar</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul A-Z</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="row">
        @forelse($courses as $course)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm card-hover">
                    @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}" class="card-img-top" alt="{{ $course->judul }}" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="fas fa-image fa-4x"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($course->judul, 50) }}</h5>
                        <p class="text-muted small">
                            <i class="fas fa-user me-1"></i> {{ $course->guru->name }}
                            <br>
                            <i class="fas fa-tag me-1"></i> {{ $course->kategori->nama }}
                        </p>
                        <p class="card-text small">{{ Str::limit($course->deskripsi, 100) }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                @if($course->harga > 0)
                                    <span class="badge bg-warning text-dark">
                                        Rp {{ number_format($course->harga, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="badge bg-success">Gratis</span>
                                @endif
                            </div>
                            <div>
                                <span class="badge bg-info">
                                    <i class="fas fa-users me-1"></i> {{ $course->enrollments_count ?? 0 }} siswa
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-primary w-100">
                            <i class="fas fa-eye me-2"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-inbox fa-4x mb-3 d-block"></i>
                    <h5>Belum ada kursus</h5>
                    <p>Silakan cek kembali nanti</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $courses->withQueryString()->links() }}
    </div>
</div>

<style>
.card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>
@endsection
