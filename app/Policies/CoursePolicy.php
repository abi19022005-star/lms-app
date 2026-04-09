<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Course;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua user yang login bisa melihat daftar kursus
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        // Admin dan pemilik kursus bisa melihat semua kursus
        if ($user->isAdmin() || $user->id === $course->guru_id) {
            return true;
        }

        // Siswa hanya bisa melihat kursus yang sudah dipublish
        if ($user->isSiswa() && $course->status === 'published') {
            return true;
        }

        // Guest hanya bisa melihat kursus yang dipublish
        return $course->status === 'published';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin dan guru bisa membuat kursus
        return $user->isAdmin() || $user->isGuru();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        // Admin bisa update semua kursus
        if ($user->isAdmin()) {
            return true;
        }

        // Guru hanya bisa update kursus miliknya sendiri
        return $user->isGuru() && $user->id === $course->guru_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        // Admin bisa delete semua kursus
        if ($user->isAdmin()) {
            return true;
        }

        // Guru hanya bisa delete kursus miliknya sendiri
        return $user->isGuru() && $user->id === $course->guru_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {
        // Hanya admin yang bisa restore kursus
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        // Hanya admin yang bisa force delete kursus
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can publish the course.
     */
    public function publish(User $user, Course $course): bool
    {
        // Admin dan pemilik kursus bisa publish/unpublish
        return $user->isAdmin() || ($user->isGuru() && $user->id === $course->guru_id);
    }

    /**
     * Determine whether the user can enroll to course.
     */
    public function enroll(User $user, Course $course): bool
    {
        // Hanya siswa yang bisa enroll
        if (!$user->isSiswa()) {
            return false;
        }

        // Kursus harus sudah dipublish
        if ($course->status !== 'published') {
            return false;
        }

        // Cek apakah sudah pernah enroll
        $alreadyEnrolled = $course->enrollments()
            ->where('user_id', $user->id)
            ->exists();

        // Tidak boleh double enroll
        return !$alreadyEnrolled;
    }

    /**
     * Determine whether the user can access course content.
     */
    public function accessContent(User $user, Course $course): bool
    {
        // Admin dan pemilik kursus bisa akses semua konten
        if ($user->isAdmin() || $user->id === $course->guru_id) {
            return true;
        }

        // Siswa hanya bisa akses konten jika sudah enroll
        if ($user->isSiswa()) {
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->exists();
            return $isEnrolled;
        }

        return false;
    }
}
