<?php

namespace App\Providers;

use App\Events\CourseCompleted;
use App\Events\QuizPassed;
use App\Listeners\GenerateCertificate;
use App\Listeners\SendCourseCompletionEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Course Completed Event - Mengirim email notifikasi
        CourseCompleted::class => [
            SendCourseCompletionEmail::class,
            // \App\Listeners\UpdateUserStatistics::class,
        ],

        // Quiz Passed Event - Generate sertifikat
        QuizPassed::class => [
            GenerateCertificate::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
