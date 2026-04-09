<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Telah Terbit</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header .logo {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
        }
        .certificate-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
        }
        .certificate-info h3 {
            margin-top: 0;
            color: #4CAF50;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .info-value {
            display: inline-block;
        }
        .verification-code {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 16px;
            text-align: center;
            letter-spacing: 2px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
        }
        .button:hover {
            background: #45a049;
        }
        .footer {
            background: #f9f9f9;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            color: #999;
            text-decoration: none;
            margin: 0 10px;
        }
        @media only screen and (max-width: 480px) {
            .container {
                margin: 10px;
            }
            .header {
                padding: 30px 20px;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🎓</div>
            <h1>Selamat! 🎉</h1>
            <p>Anda Telah Mendapatkan Sertifikat</p>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $certificate->user->name }}</strong>,</p>

            <p>Selamat! Anda telah berhasil menyelesaikan kursus <strong>{{ $certificate->course->judul }}</strong> dengan nilai memuaskan.</p>

            <div class="certificate-info">
                <h3>Detail Sertifikat:</h3>
                <div class="info-row">
                    <span class="info-label">Nama:</span>
                    <span class="info-value">{{ $certificate->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kursus:</span>
                    <span class="info-value">{{ $certificate->course->judul }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Terbit:</span>
                    <span class="info-value">{{ $certificate->issued_at->translatedFormat('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kode Verifikasi:</span>
                    <span class="info-value">{{ $certificate->kode_unik }}</span>
                </div>
            </div>

            <div class="verification-code">
                <strong>Kode Verifikasi:</strong> {{ $certificate->kode_unik }}
            </div>

            <p>Sertifikat ini dapat diverifikasi secara online menggunakan kode unik di atas.</p>

            <div style="text-align: center;">
                <a href="{{ route('certificates.download', $certificate) }}" class="button">
                    📥 Unduh Sertifikat PDF
                </a>
            </div>

            <p style="margin-top: 30px; font-size: 14px;">
                <small>⚠️ Sertifikat ini adalah bukti resmi kelulusan Anda. Harap simpan dengan baik.</small>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ setting('app_name', config('app.name')) }}. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, harap tidak membalas email ini.</p>
            <div class="social-links">
                <a href="#">Website</a> |
                <a href="#">Facebook</a> |
                <a href="#">Instagram</a>
            </div>
        </div>
    </div>
</body>
</html>
