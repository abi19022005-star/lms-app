<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Models\Course;
use App\Observers\CourseObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Course::observe(CourseObserver::class);
        Blade::directive('role', function ($expression) {
            return "<?php if(auth()->check() && in_array(auth()->user()->role, {$expression})): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        Blade::if('admin', function () {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            return Auth::check() && $user?->isAdmin();
        });

        Blade::if('guru', function () {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            return Auth::check() && $user?->isGuru();
        });

        Blade::if('siswa', function () {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            return Auth::check() && $user?->isSiswa();
        });
    }
}
