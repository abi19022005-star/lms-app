<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $certificates = $user->certificates()
            ->with('course')
            ->orderBy('issued_at', 'desc')
            ->paginate(12);

        return view('certificates.index', compact('certificates'));
    }

    public function show(Certificate $certificate)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($certificate->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        // 🔥 LOG VIEW CERTIFICATE
        logActivity([
            'action' => 'view_certificate',
            'action_type' => 'READ',
            'module' => 'certificate',
            'model_type' => Certificate::class,
            'model_id' => $certificate->id,
            'model_name' => $certificate->course->judul ?? 'Certificate',
            'description' => 'User melihat certificate',
        ]);

        return view('certificates.show', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($certificate->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        // 🔥 LOG DOWNLOAD CERTIFICATE
        logActivity([
            'action' => 'download_certificate',
            'action_type' => 'READ',
            'module' => 'certificate',
            'model_type' => Certificate::class,
            'model_id' => $certificate->id,
            'model_name' => $certificate->course->judul ?? 'Certificate',
            'description' => 'User download certificate',
        ]);

        $pdf = Pdf::loadView('certificates.pdf', compact('certificate'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('sertifikat-' . $certificate->course->judul . '.pdf');
    }

    public function verify($code)
    {
        $certificate = Certificate::where('kode_unik', $code)
            ->with(['user', 'course'])
            ->first();

        if (!$certificate) {

            // 🔥 LOG VERIFY GAGAL
            logActivity([
                'action' => 'verify_certificate',
                'action_type' => 'READ',
                'module' => 'certificate',
                'description' => 'Verifikasi gagal: kode tidak ditemukan',
                'is_error' => true,
            ]);

            return view('certificates.verify', [
                'valid' => false,
                'message' => 'Sertifikat tidak ditemukan atau kode verifikasi salah.'
            ]);
        }

        // 🔥 LOG VERIFY BERHASIL
        logActivity([
            'action' => 'verify_certificate',
            'action_type' => 'READ',
            'module' => 'certificate',
            'model_type' => Certificate::class,
            'model_id' => $certificate->id,
            'model_name' => $certificate->course->judul ?? 'Certificate',
            'description' => 'Verifikasi certificate berhasil',
        ]);

        return view('certificates.verify', [
            'valid' => true,
            'certificate' => $certificate,
            'message' => 'Sertifikat valid!'
        ]);
    }

    public function preview(Certificate $certificate)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($certificate->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        // 🔥 LOG PREVIEW
        logActivity([
            'action' => 'preview_certificate',
            'action_type' => 'READ',
            'module' => 'certificate',
            'model_type' => Certificate::class,
            'model_id' => $certificate->id,
            'model_name' => $certificate->course->judul ?? 'Certificate',
            'description' => 'User preview certificate',
        ]);

        return view('certificates.preview', compact('certificate'));
    }
}
