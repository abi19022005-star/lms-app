<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Lesson;
use Illuminate\Auth\Access\Response;

class LessonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua user bisa melihat daftar lesson
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lesson $lesson): bool
    {
        $course = $lesson->course;

        // Admin bisa melihat semua lesson
        if ($user->isAdmin()) {
            return true;
        }

        // Guru bisa melihat lesson di kursus miliknya
        if ($user->isGuru() && $user->id === $course->guru_id) {
            return true;
        }

        // Siswa hanya bisa melihat lesson jika sudah enroll di kursus
        if ($user->isSiswa()) {
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->exists();
            return $isEnrolled;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?Lesson $lesson = null): bool
    {
        // Admin bisa membuat lesson di kursus apapun
        if ($user->isAdmin()) {
            return true;
        }

        // Guru bisa membuat lesson di kursus miliknya
        if ($user->isGuru() && $lesson) {
            return $user->id === $lesson->course->guru_id;
        }

        return $user->isGuru();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lesson $lesson): bool
    {
        $course = $lesson->course;

        // Admin bisa update semua lesson
        if ($user->isAdmin()) {
            return true;
        }

        // Guru hanya bisa update lesson di kursus miliknya
        return $user->isGuru() && $user->id === $course->guru_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lesson $lesson): bool
    {
        $course = $lesson->course;

        // Admin bisa delete semua lesson
        if ($user->isAdmin()) {
            return true;
        }

        // Guru hanya bisa delete lesson di kursus miliknya
        return $user->isGuru() && $user->id === $course->guru_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lesson $lesson): bool
    {
        // Hanya admin yang bisa restore lesson
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lesson $lesson): bool
    {
        // Hanya admin yang bisa force delete lesson
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can complete the lesson.
     */
    public function complete(User $user, Lesson $lesson): bool
    {
        // Hanya siswa yang bisa menyelesaikan lesson
        if (!$user->isSiswa()) {
            return false;
        }

        $course = $lesson->course;

        // Cek apakah siswa sudah enroll di kursus
        $enrollment = $course->enrollments()
            ->where('user_id', $user->id)
            ->first();

        if (!$enrollment) {
            return false;
        }

        // Cek apakah lesson belum pernah diselesaikan
        $alreadyCompleted = $lesson->completions()
            ->where('user_id', $user->id)
            ->exists();

        return !$alreadyCompleted;
    }

    /**
     * Determine whether the user can reorder lessons.
     */
    public function reorder(User $user, Lesson $lesson): bool
    {
        $course = $lesson->course;

        // Admin dan pemilik kursus bisa reorder lesson
        return $user->isAdmin() || ($user->isGuru() && $user->id === $course->guru_id);
    }
}
