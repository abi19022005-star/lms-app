<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Policies\CoursePolicy;
use App\Policies\LessonPolicy;
use App\Policies\QuizPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        Lesson::class => LessonPolicy::class,
        Quiz::class => QuizPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Additional Gates (opsional)
        Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // Gate untuk mengakses admin area
        Gate::define('access-admin', function ($user) {
            return $user->isAdmin();
        });

        // Gate untuk mengakses teacher area
        Gate::define('access-teacher', function ($user) {
            return $user->isGuru() || $user->isAdmin();
        });

        // Gate untuk mengakses student area
        Gate::define('access-student', function ($user) {
            return $user->isSiswa() || $user->isAdmin();
        });
    }
}
