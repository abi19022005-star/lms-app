<?php

namespace App\Events;

use App\Models\QuizAttempt;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizPassed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The quiz attempt instance.
     *
     * @var \App\Models\QuizAttempt
     */
    public $quizAttempt;

    /**
     * Create a new event instance.
     */
    public function __construct(QuizAttempt $quizAttempt)
    {
        $this->quizAttempt = $quizAttempt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('quiz-passed.' . $this->quizAttempt->user_id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->quizAttempt->user_id,
            'quiz_id' => $this->quizAttempt->quiz_id,
            'quiz_title' => $this->quizAttempt->quiz->judul,
            'course_id' => $this->quizAttempt->quiz->course_id,
            'course_title' => $this->quizAttempt->quiz->course->judul,
            'total_score' => $this->quizAttempt->total_score,
            'passing_score' => $this->quizAttempt->quiz->passing_score,
            'submitted_at' => $this->quizAttempt->submitted_at,
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'quiz.passed';
    }

    /**
     * Determine if the user has already received a certificate.
     */
    public function hasCertificate(): bool
    {
        return \App\Models\Certificate::where('user_id', $this->quizAttempt->user_id)
            ->where('course_id', $this->quizAttempt->quiz->course_id)
            ->exists();
    }

    /**
     * Get the enrollment status for the course.
     */
    public function getEnrollmentStatus()
    {
        return \App\Models\Enrollment::where('user_id', $this->quizAttempt->user_id)
            ->where('course_id', $this->quizAttempt->quiz->course_id)
            ->first();
    }
}
