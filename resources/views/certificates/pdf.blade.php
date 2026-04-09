<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
    @page {
        size: A4 landscape;
        margin: 0;
    }

    body {
        margin: 0;
        font-family: DejaVu Sans, sans-serif;
    }

    /* Background */
    .certificate {
        width: 100%;
        height: 100%;
        position: relative;
        background: url("{{ public_path('certificate/bg.png') }}") no-repeat center;
        background-size: cover;
    }

    /* Judul */
    .title {
        position: absolute;
        top: 90px;
        width: 100%;
        text-align: center;
        font-size: 48px;
        font-weight: bold;
        color: #e11d48;
        letter-spacing: 5px;
    }

    /* Subtitle */
    .subtitle {
        position: absolute;
        top: 140px;
        width: 100%;
        text-align: center;
        font-size: 18px;
    }

    /* Diberikan */
    .given {
        position: absolute;
        top: 200px;
        width: 100%;
        text-align: center;
        font-size: 18px;
    }

    /* Nama */
    .name {
        position: absolute;
        top: 240px;
        width: 100%;
        text-align: center;
        font-size: 52px;
        font-family: "Brush Script MT", cursive;
        color: red;
    }

    /* Sebagai */
    .role-label {
        position: absolute;
        top: 320px;
        width: 100%;
        text-align: center;
        font-size: 20px;
    }

    /* Jabatan */
    .role {
        position: absolute;
        top: 350px;
        width: 100%;
        text-align: center;
        font-size: 26px;
        font-weight: bold;
    }

    /* Deskripsi */
    .desc {
        position: absolute;
        top: 400px;
        width: 70%;
        left: 15%;
        text-align: center;
        font-size: 16px;
        line-height: 1.6;
    }

    /* TTD kiri */
    .ttd-left {
        position: absolute;
        bottom: 130px;
        left: 140px;
        text-align: center;
    }

    .ttd-left img {
        width: 120px;
    }

    /* TTD kanan */
    .ttd-right {
        position: absolute;
        bottom: 130px;
        right: 140px;
        text-align: center;
    }

    .ttd-right img {
        width: 120px;
    }

    /* Nama TTD */
    .ttd-name {
        margin-top: 10px;
        font-size: 14px;
    }

    /* Stempel */
    .stamp {
        position: absolute;
        bottom: 140px;
        left: 50%;
        transform: translateX(-50%);
    }

    .stamp img {
        width: 120px;
        opacity: 0.9;
    }
</style>
</head>

<body>

<div class="certificate">

    <div class="title">CERTIFICATE</div>
    <div class="subtitle">OF COMPLETION</div>

    <p class="text">This certificate is awarded to</p>

    <div class="name">
        {{ strtoupper($certificate->user->name) }}
    </div>

    <p class="text">for successfully completing the course</p>

    <div class="course">
        {{ $certificate->course->judul }}
    </div>

    <p class="text">
        on {{ $certificate->issued_at->format('d F Y') }}
    </p>

    <div class="code">
        Certificate Code: {{ $certificate->kode_unik }}
    </div>

    {{-- <!-- TTD kiri -->
    <div class="ttd-left">
        <img src="{{ public_path('certificate/ttd1.png') ?? '' }}">
        <div class="ttd-name">Yogi Eka Putra</div>
    </div>

    <!-- TTD kanan -->
    <div class="ttd-right">
        <img src="{{ public_path('certificate/ttd2.png') ?? '' }}">
        <div class="ttd-name">Dean Deyopa</div>
    </div>

    <!-- Stempel -->
    <div class="stamp">
        <img src="{{ public_path('certificate/stempel.png') ?? '' }}">
    </div> --}}
    <div class="signature-section">

        <div class="signature-item">
            <div class="line"></div>
            <div>{{ $certificate->course->guru->name ?? 'Instructor' }}</div>
            <small>Instructor</small>
        </div>

        <div class="signature-item">
            <div class="line"></div>
            <div>LMS Platform</div>
            <small>Authorized</small>
        </div>

    </div>

</div>

</body>
</html>
