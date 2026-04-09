<?php

namespace App\Listeners;

use App\Events\CourseCompleted;
use App\Mail\CourseCompletionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendCourseCompletionEmail implements ShouldQueue
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
    public function handle(CourseCompleted $event): void
    {
        $enrollment = $event->enrollment;
        $user = $enrollment->user;
        $course = $enrollment->course;

        Log::info('SendCourseCompletionEmail listener triggered', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'user_email' => $user->email,
        ]);

        try {
            // Kirim email notifikasi
            Mail::to($user->email)
                ->send(new CourseCompletionNotification($enrollment));

            Log::info('Course completion email sent successfully', [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send course completion email', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'error' => $e->getMessage(),
            ]);

            throw $e; // Re-throw untuk retry
        }
    }
}
