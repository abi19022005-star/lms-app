<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Enrollment Kursus</title>
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
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
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
        .course-info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2196F3;
        }
        .course-info h3 {
            margin-top: 0;
            color: #1976D2;
        }
        .button {
            display: inline-block;
            background: #2196F3;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
        }
        .button:hover {
            background: #1976D2;
        }
        .progress-tip {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            background: #f9f9f9;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
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
            <div class="logo">📚</div>
            <h1>Selamat Datang di Kursus!</h1>
            <p>Enrollment Berhasil</p>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $enrollment->user->name }}</strong>,</p>

            <p>Selamat! Anda telah berhasil terdaftar di kursus:</p>

            <div class="course-info">
                <h3>{{ $enrollment->course->judul }}</h3>
                <p><strong>Instruktur:</strong> {{ $enrollment->course->guru->name }}</p>
                <p><strong>Kategori:</strong> {{ $enrollment->course->kategori->nama }}</p>
                <p><strong>Total Lesson:</strong> {{ $enrollment->course->lessons->count() }} lesson</p>
                <p><strong>Status:</strong> <span style="color: #4CAF50;">Active</span></p>
            </div>

            <div class="progress-tip">
                <strong>💡 Tips Belajar:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    <li>Pelajari lesson secara berurutan</li>
                    <li>Tandai "Selesai" setiap selesai satu lesson</li>
                    <li>Kerjakan kuis setelah menyelesaikan semua lesson</li>
                    <li>Dapatkan sertifikat setelah lulus kuis</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('courses.show', $enrollment->course) }}" class="button">
                    🚀 Mulai Belajar Sekarang
                </a>
            </div>

            <p style="margin-top: 30px; font-size: 14px;">
                <small>Progres belajar Anda akan tersimpan secara otomatis. Selamat belajar!</small>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ setting('app_name', config('app.name')) }}. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, harap tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
