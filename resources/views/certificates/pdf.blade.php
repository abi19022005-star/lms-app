<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Certificate PDF</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
        }

        .certificate {
            width: 92.5%;
            height: 89%;
            position: relative;
            background: url("{{ public_path('certificate/bg.png') }}") no-repeat center;
            background-size: cover;
            padding: 40px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .certificate-container {
            width: 100%;
            text-align: center;
        }

        .certificate-header h1 {
            font-size: 32px;
            letter-spacing: 3px;
            margin: 0;
            color: #4f46e5;
        }

        .certificate-header p {
            font-size: 14px;
            margin-top: 5px;
        }

        .certificate-body {
            margin-top: 30px;
        }

        .certificate-body h2 {
            font-size: 26px;
            margin: 20px 0;
        }

        .certificate-body h3 {
            font-size: 22px;
            margin: 15px 0;
            color: #4f46e5;
        }

        .certificate-body p {
            font-size: 14px;
        }

        .certificate-info {
            width: 100%;
            margin-top: 60px;
        }

        .certificate-info table {
            width: 100%;
        }

        .certificate-info td {
            text-align: center;
        }

        .line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 10px auto;
        }

        .certificate-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
        }

        .code {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="certificate">

    <div class="certificate-container">

        <!-- HEADER -->
        <br><br><br>
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
            <table>
                <tr>
                    <td>
                        <div class="line"></div>
                        <p>{{ $certificate->course->guru->name ?? 'Instructor' }}</p>
                        <small>Instructor</small>
                    </td>

                    <td>
                        <div class="line"></div>
                        <p>LMS Platform</p>
                        <small>Authorized</small>
                    </td>
                </tr>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="certificate-footer">
            Sertifikat ini dapat diverifikasi menggunakan kode unik secara online.
        </div>

    </div>

</div>

</body>
</html>
