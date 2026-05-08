<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Certificate Preview</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background: #f3f4f6;
            margin: 0;
            padding: 30px;
        }

        .certificate-container {
            max-width: 900px;
            margin: auto;
            background: #ffffff;
            padding: 40px;
            border: 10px solid #4f46e5;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .certificate-header h1 {
            font-size: 32px;
            letter-spacing: 3px;
            margin: 0;
            color: #4f46e5;
        }

        .certificate-header p {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .certificate-body {
            text-align: center;
            margin-top: 20px;
        }

        .certificate-body h2 {
            font-size: 26px;
            margin: 20px 0;
            color: #111827;
        }

        .certificate-body h3 {
            font-size: 22px;
            margin: 15px 0;
            color: #4f46e5;
        }

        .certificate-body p {
            font-size: 14px;
            color: #374151;
        }

        .certificate-info {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .certificate-info div {
            text-align: center;
        }

        .line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 10px auto;
        }

        .certificate-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        .code {
            margin-top: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .actions {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 5px;
        }

        .btn:hover {
            background: #4338ca;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .actions {
                display: none;
            }
        }
        
    </style>
</head>
<body>

<div class="certificate-container">

    <!-- HEADER -->
    <div class="certificate-header">
        <h1>CERTIFICATE</h1>
        <p>OF COMPLETION</p>
    </div>

    <!-- BODY -->
    <div class="certificate-body">

        <p>This certificate is awarded to</p>

        <h3>{{ $certificate->user->name }}</h3>

        <p>for successfully completing the course</p>

        <h2>{{ $certificate->course->judul }}</h2>

        <p>on {{ $certificate->issued_at->format('d F Y') }}</p>

        <div class="code">
            Certificate Code: {{ $certificate->kode_unik }}
        </div>

    </div>

    <!-- SIGNATURE -->
    <div class="certificate-info">

        <div>
            <div class="line"></div>
            <p>{{ $certificate->course->guru->name ?? 'Instructor' }}</p>
            <small>Instructor</small>
        </div>

        <div>
            <div class="line"></div>
            <p>LMS Platform</p>
            <small>Authorized</small>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="certificate-footer">
        Sertifikat ini dapat diverifikasi menggunakan kode unik secara online.
    </div>

</div>

<!-- ACTION BUTTON -->
<div class="actions">
    <a href="{{ route('certificates.download', $certificate) }}" class="btn">
        Download PDF
    </a>
</div>

</body>
</html>
