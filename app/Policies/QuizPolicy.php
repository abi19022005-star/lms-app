<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Auth\Access\Response;

class QuizPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Quiz $quiz): bool
    {
        $course = $quiz->course;

        // Admin bisa melihat semua kuis
        if ($user->isAdmin()) {
            return true;
        }

        // Guru bisa melihat kuis di kursus miliknya
        if ($user->isGuru() && $user->id === $course->guru_id) {
            return true;
        }

        // Siswa hanya bisa melihat kuis jika sudah menyelesaikan semua lesson
        if ($user->isSiswa()) {
            $enrollment = $course->enrollments()
                ->where('user_id', $user->id)
                ->first();

            return $enrollment && $enrollment->status === 'completed';
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?Quiz $quiz = null): bool
    {
        // Admin bisa membuat kuis di kursus apapun
        if ($user->isAdmin()) {
            return true;
        }

        // Guru bisa membuat kuis di kursus miliknya
        if ($user->isGuru() && $quiz) {
            return $user->id === $quiz->course->guru_id;
        }

        return $user->isGuru();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Quiz $quiz): bool
    {
        $course = $quiz->course;

        // Admin bisa update semua kuis
        if ($user->isAdmin()) {
            return true;
        }

        // Guru hanya bisa update kuis di kursus miliknya
        return $user->isGuru() && $user->id === $course->guru_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Quiz $quiz): bool
    {
        $course = $quiz->course;

        // Admin bisa delete semua kuis
        if ($user->isAdmin()) {
            return true;
        }

        // Guru hanya bisa delete kuis di kursus miliknya
        return $user->isGuru() && $user->id === $course->guru_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Quiz $quiz): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Quiz $quiz): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can attempt the quiz.
     */
    public function attempt(User $user, Quiz $quiz): bool
    {
        // Hanya siswa yang bisa mencoba kuis
        if (!$user->isSiswa()) {
            return false;
        }

        $course = $quiz->course;

        // Cek apakah siswa sudah menyelesaikan semua lesson
        $enrollment = $course->enrollments()
            ->where('user_id', $user->id)
            ->first();

        if (!$enrollment || $enrollment->status !== 'completed') {
            return false;
        }

        // Cek apakah sudah pernah mencoba kuis
        $hasAttempted = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->exists();

        // Tidak boleh mencoba ulang (kecuali direset oleh guru)
        return !$hasAttempted;
    }

    /**
     * Determine whether the user can submit quiz.
     */
    public function submit(User $user, Quiz $quiz): bool
    {
        // Hanya siswa yang bisa submit kuis
        if (!$user->isSiswa()) {
            return false;
        }

        // Cek apakah sudah ada attempt dan belum disubmit
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->first();

        return $attempt && !$attempt->submitted_at;
    }

    /**
     * Determine whether the user can view results.
     */
    public function viewResult(User $user, QuizAttempt $attempt): bool
    {
        // Siswa bisa melihat hasil kuisnya sendiri
        if ($user->isSiswa() && $user->id === $attempt->user_id) {
            return true;
        }

        // Guru bisa melihat hasil kuis dari siswanya
        if ($user->isGuru() && $user->id === $attempt->quiz->course->guru_id) {
            return true;
        }

        // Admin bisa melihat semua hasil
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can grade the quiz.
     */
    public function grade(User $user, QuizAttempt $attempt): bool
    {
        // Hanya guru yang bisa menilai essay
        if (!$user->isGuru()) {
            return false;
        }

        // Guru hanya bisa menilai kuis di kursus miliknya
        return $user->id === $attempt->quiz->course->guru_id;
    }

    /**
     * Determine whether the user can reset the quiz.
     */
    public function reset(User $user, Quiz $quiz): bool
    {
        $course = $quiz->course;

        // Admin dan pemilik kursus bisa reset kuis siswa
        return $user->isAdmin() || ($user->isGuru() && $user->id === $course->guru_id);
    }
}
