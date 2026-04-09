<?php

namespace App\Listeners;

use App\Events\QuizPassed;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Mail\CertificateIssued;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateCertificate implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Handle the event.
     */
    public function handle(QuizPassed $event): void
    {
        $quizAttempt = $event->quizAttempt;
        $user = $quizAttempt->user;
        $course = $quizAttempt->quiz->course;

        Log::info('GenerateCertificate listener triggered', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'total_score' => $quizAttempt->total_score,
            'passing_score' => $quizAttempt->quiz->passing_score
        ]);

        // 1. Cek apakah user sudah menyelesaikan semua lesson
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment || $enrollment->status !== 'completed') {
            Log::warning('Certificate not generated: Course not completed', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'enrollment_status' => $enrollment ? $enrollment->status : 'no_enrollment'
            ]);
            return;
        }

        // 2. Cek apakah nilai sudah mencapai passing score
        if ($quizAttempt->total_score < $quizAttempt->quiz->passing_score) {
            Log::warning('Certificate not generated: Score below passing', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'score' => $quizAttempt->total_score,
                'passing_score' => $quizAttempt->quiz->passing_score
            ]);
            return;
        }

        // 3. Cek apakah sertifikat sudah ada
        $existingCertificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingCertificate) {
            Log::info('Certificate already exists', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'certificate_id' => $existingCertificate->id
            ]);
            return;
        }

        // 4. Generate kode unik untuk sertifikat
        $uniqueCode = $this->generateUniqueCode($user, $course);

        // 5. Buat sertifikat di database
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'kode_unik' => $uniqueCode,
            'issued_at' => now(),
        ]);

        Log::info('Certificate created in database', [
            'certificate_id' => $certificate->id,
            'unique_code' => $uniqueCode
        ]);

        // 6. Generate PDF sertifikat (opsional, bisa disimpan ke storage)
        $this->generatePdfCertificate($certificate);

        // 7. Kirim email notifikasi ke siswa
        $this->sendEmailNotification($certificate);

        // 8. Log aktivitas (opsional)
        $this->logActivity($certificate);
    }

    /**
     * Generate unique code for certificate.
     */
    private function generateUniqueCode($user, $course): string
    {
        $prefix = 'CERT';
        $userId = str_pad($user->id, 4, '0', STR_PAD_LEFT);
        $courseId = str_pad($course->id, 4, '0', STR_PAD_LEFT);
        $timestamp = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -6));

        return "{$prefix}-{$userId}-{$courseId}-{$timestamp}-{$random}";
    }

    /**
     * Generate PDF certificate and save to storage.
     */
    private function generatePdfCertificate(Certificate $certificate): void
    {
        try {
            $pdf = PDF::loadView('certificate.pdf', compact('certificate'));
            $pdf->setPaper('a4', 'landscape');

            $filename = "certificates/certificate-{$certificate->kode_unik}.pdf";
            Storage::disk('public')->put($filename, $pdf->output());

            // Update certificate with PDF path (if you add this column)
            // $certificate->pdf_path = $filename;
            // $certificate->save();

            Log::info('PDF certificate generated', ['filename' => $filename]);
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF certificate', [
                'error' => $e->getMessage(),
                'certificate_id' => $certificate->id
            ]);
        }
    }

    /**
     * Send email notification to student.
     */
    private function sendEmailNotification(Certificate $certificate): void
    {
        try {
            Mail::to($certificate->user->email)
                ->queue(new CertificateIssued($certificate));

            Log::info('Certificate email queued', [
                'user_email' => $certificate->user->email,
                'certificate_id' => $certificate->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send certificate email', [
                'error' => $e->getMessage(),
                'user_email' => $certificate->user->email
            ]);
        }
    }

    /**
     * Log certificate generation activity.
     */
    private function logActivity(Certificate $certificate): void
    {
        // If you have activity log system
        // ActivityLog::create([
        //     'user_id' => $certificate->user_id,
        //     'action' => 'certificate_generated',
        //     'description' => "Certificate generated for course: {$certificate->course->judul}",
        //     'metadata' => [
        //         'certificate_id' => $certificate->id,
        //         'course_id' => $certificate->course_id,
        //         'unique_code' => $certificate->kode_unik
        //     ]
        // ]);

        // Simple log for now
        Log::info('Certificate activity logged', [
            'user_id' => $certificate->user_id,
            'user_name' => $certificate->user->name,
            'course_name' => $certificate->course->judul,
            'issued_at' => $certificate->issued_at
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(QuizPassed $event, \Throwable $exception): void
    {
        Log::error('GenerateCertificate listener failed', [
            'user_id' => $event->quizAttempt->user_id ?? null,
            'quiz_id' => $event->quizAttempt->quiz_id ?? null,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
